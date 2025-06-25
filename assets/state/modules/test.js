import axios from 'axios';

export const state = {
    test: null,
    loading: false,
    error: null,
    submissionStatus: null,
    previousAnswers: null,
};

export const mutations = {
    setTest(state, test) {
        state.test = test;
        state.previousAnswers = test.previousRawAnswers || null;
    },
    setLoading(state, loading) {
        state.loading = loading;
    },
    setError(state, error) {
        state.error = error;
    },
    setSubmissionStatus(state, status) {
        state.submissionStatus = status;
    },
    clearTest(state) {
        state.test = null;
        state.previousAnswers = null;
    },
    setSubmissionData(state, payload) {
        state.previousAnswers = payload.answers || null;
    },
    setScores(state, scores) {
        state.scores = scores;
    }
};

export const actions = {
    async loadTest({ commit }, testType) {
        commit('clearTest');
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get(`/api/test/${testType}/questions`);
            commit('setTest', response.data);
        } catch (error) {
            commit('setError', error.message || `Erreur lors du chargement du test (${testType})`);
        } finally {
            commit('setLoading', false);
        }
    },

    async submitAnswers({ commit }, { testType, answers }) {
        const response = await fetch(`/api/test/${testType.toLowerCase()}/submit`, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ answers }),
        });

        if (!response.ok) {
            throw new Error('Erreur API');
        }

        const data = await response.json();

        // Save results and answers in store for UI updates
        commit('setSubmissionData', data);

        return data; // contains results, interpretation, answers
    },
    async loadTestResults({ commit }) {
        commit('setLoading', true);
        commit('setError', null);
        try {
            const response = await axios.get('/api/test/personality/results');
            commit('setScores', response.data.scores); // Tu pourras utiliser scores dans le composant
        } catch (error) {
            commit('setError', error.message || 'Erreur lors du chargement des résultats');
            return null;
        } finally {
            commit('setLoading', false);
        }
    }
};

export const getters = {
    questions: (state) => state.test?.questions || [],
    testTitle: (state) => state.test?.title || '',
    submissionStatus: (state) => state.submissionStatus,
    previousAnswers: (state) => state.previousAnswers,
    scores: (state) => state.scores || {},
};

export default {
    namespaced: true,
    state,
    mutations,
    actions,
    getters,

};
