import axios from 'axios'; // Ensure axios is imported

export const state = {
    user: null,
    loading: false,
    error: null
};

export const getters = {
    isAuthenticated: state => {
        // console.log('[Vuex Getter isAuthenticated] state.user:', state.user ? JSON.parse(JSON.stringify(state.user)) : null);
        return !!state.user;
    },
    userFullName: state => {
        console.log('[Vuex Getter userFullName] Getter invoked. Current state.user:', state.user ? JSON.parse(JSON.stringify(state.user)) : null);
        if (state.user && typeof state.user.personalInfo.firstName === 'string' && state.user.personalInfo.firstName.trim() !== '' &&
            typeof state.user.personalInfo.lastName === 'string' && state.user.personalInfo.lastName.trim() !== '') {
            const fullName = `${state.user.personalInfo.firstName} ${state.user.personalInfo.lastName}`;
            console.log('[Vuex Getter userFullName] Conditions MET. Calculated fullName:', fullName);
            return fullName;
        }
        console.warn('[Vuex Getter userFullName] Conditions NOT MET. firstName:', state.user?.personalInfo.firstName, '(type:', typeof state.user?.personalInfo.firstName, '), lastName:', state.user?.personalInfo.lastName, '(type:', typeof state.user?.personalInfo.lastName, ')');
        return ''; // Return empty string if conditions aren't met
    },
    userEmail: state => state.user?.email || '',
    userRole: state => state.user?.roles?.[0]?.replace('ROLE_', '') || '',
    userPhoneNumber: state => state.user?.phoneNumber || '',
    userSex: state => state.user?.personalInfo.Sex || '',
    userDateOfBirth: state => state.user?.personalInfo.dateOfBirth || '',
    userNiveauDetude: state => state.user?.academicInfo.niveauDetude || '',
    userFiliere: state => state.user?.academicInfo.filiere || '',
    userTypeEtablissement: state => state.user?.academicInfo.typeEtablissement || '',
    userAnneeObtentionBac: state => state.user?.academicInfo.anneeObtentionBac || '',
    lycee: state => state.user?.academicInfo.lycee || '',
    nomTuteur: state => state.user?.academicInfo.nomTuteur || '',
    telTuteur: state => state.user?.academicInfo.telTuteur || '',




};

export const mutations = {
    setUser(state, user) {
        console.log('[Vuex Mutation setUser] Payload (user object to set):', user ? JSON.parse(JSON.stringify(user)) : null);
        state.user = user;
        console.log('[Vuex Mutation setUser] state.user AFTER setting:', state.user ? JSON.parse(JSON.stringify(state.user)) : null);
    },
    setLoading(state, loading) {
        // console.log('[Vuex Mutation setLoading] Loading:', loading);
        state.loading = loading;
    },
    setError(state, error) {
        if (error) {
            console.error('[Vuex Mutation setError] Error set:', error);
        } else {
            console.log('[Vuex Mutation setError] Error cleared.');
        }
        state.error = error;
    }
};

export const actions = {
    async fetchUser({ commit }) {
        console.log('[Vuex Action fetchUser] Action started.');
        commit('setLoading', true);
        commit('setError', null); // Clear previous errors
        try {
            console.log('[Vuex Action fetchUser] Making API call to /api/me.');
            const res = await axios.get('user/api/me'); // Ensure this path is correct and axios is configured
            console.log('[Vuex Action fetchUser] RAW API response status:', res.status);
            console.log('[Vuex Action fetchUser] RAW API response data (res.data):', res.data ? JSON.parse(JSON.stringify(res.data)) : null);
            if (res.data && res.data.id) {
                commit('setUser', res.data);
            } else {
                commit('setError', 'Incomplete user data received from server.');
                commit('setUser', null);
            }

            if (res.data && typeof res.data.id !== 'undefined') { // Basic check if it looks like a user object
                console.log('[Vuex Action fetchUser] Committing setUser with data:', JSON.parse(JSON.stringify(res.data)));
                commit('setUser', res.data);
            } else {
                const errorMsg = '[Vuex Action fetchUser] API response data is missing or malformed.';
                console.error(errorMsg, res.data);
                commit('setError', 'Malformed user data from API');
                commit('setUser', null); // Important to clear user on bad data
            }
        } catch (err) {
            console.error('[Vuex Action fetchUser] CATCH block: Error fetching user.', err);
            let errorMessage = 'Failed to fetch user.';
            if (err.response) {
                console.error('[Vuex Action fetchUser] Error response data:', err.response.data);
                console.error('[Vuex Action fetchUser] Error response status:', err.response.status);
                errorMessage = err.response?.data?.message || `API error (status: ${err.response.status})`;
            } else if (err.request) {
                console.error('[Vuex Action fetchUser] Error: No response received from server. Request details:', err.request);
                errorMessage = 'No response from server.';
            } else {
                console.error('[Vuex Action fetchUser] Error message:', err.message);
                errorMessage = `Request setup error: ${err.message}`;
            }
            commit('setError', errorMessage);
            commit('setUser', null); // Ensure user is null on error
        } finally {
            console.log('[Vuex Action fetchUser] Action finished.');
            commit('setLoading', false);
        }
    },
    logout({ commit }) {
        console.log('[Vuex Action logout] Logging out.');
        commit('setUser', null);
    }
};