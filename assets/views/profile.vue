<template>
  <Layout>
    <b-row>
      <b-col lg="5">
        <b-card no-body>
          <b-card-header class="align-items-center d-flex">
            <b-card-title class="mb-0 flex-grow-1"
            >Informations de l'utilisateur</b-card-title
            >
          </b-card-header>

          <b-card-body>
            <p class="text-muted">
              Si toutes les informations vous semblent correctes, <b>ne touchez à rien !</b>
              <!-- Display user's full name from getter for verification -->
              <span v-if="isAuthenticated">Bonjour {{ userFullName }} ({{ userEmail }})</span>
            </p>
            <div class="live-preview">
              <form v-on:submit.prevent="submitPersonalInfos()">
                <b-row>
                  <b-col md="6">
                    <div class="mb-3">
                      <label for="nom" class="form-label"
                      >Nom <span class="text-danger">*</span></label
                      >
                      <input v-model="datas.nom"
                             type="text"
                             class="form-control"
                             placeholder="Votre nom..."
                             id="nom"
                             required
                      />
                    </div>
                  </b-col>
                  <b-col md="6">
                    <div class="mb-3">
                      <label for="prenom" class="form-label"
                      >Prénom <span class="text-danger">*</span></label
                      >
                      <input
                          v-model="datas.prenom"
                          type="text"
                          class="form-control"
                          placeholder="Votre prénom..."
                          id="prenom"
                          required
                      />
                    </div>
                  </b-col>
                  <b-col md="6">
                    <div class="mb-3">
                      <label for="age" class="form-label"
                      >Date de Naissance <span class="text-danger">*</span></label
                      > <!-- Changed label to be more accurate -->
                      <input v-model="datas.age"
                             type="date"
                             class="form-control"
                             placeholder="Votre date de naissance..."
                             id="age"
                             required
                      />
                    </div>
                  </b-col>
                  <b-col md="6">
                    <div class="mb-3">
                      <label for="tel" class="form-label"
                      >Téléphone <span class="text-danger">*</span></label
                      >
                      <input
                          v-model="datas.tel"
                          placeholder="06..."
                          type="text"
                          class="form-control"
                          id="tel"
                          required
                          oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)"
                      />
                    </div>
                  </b-col>
                  <b-col md="6">
                    <div class="mb-3">
                      <label for="email" class="form-label"
                      >E-mail <span class="text-danger">*</span></label
                      >
                      <input
                          v-model="datas.email"
                          type="email"
                          class="form-control"
                          placeholder="Votre e-mail..."
                          id="email"
                          required
                      />
                    </div>
                  </b-col>
                  <!-- Field for Sex/Genre if you want to display/edit -->
                  <b-col md="6">
                    <div class="mb-3">
                      <label for="genre" class="form-label">Genre</label>
                      <Multiselect
                          v-model="datas.genre"
                          :options="['Homme', 'Femme', 'Autre']" placeholder="Votre genre..."/>
                    </div>
                  </b-col>



                  <b-col lg="12">
                    <div class="d-flex justify-content-center">
                      <b-button
                          v-if="!Constraints.stepLoading"
                          type="submit"
                          variant="primary"
                      >
                        Confirmer les modifications
                      </b-button>
                      <b-button v-else variant="primary" disabled
                      ><span
                          class="spinner-border spinner-border-sm"
                          role="status"
                          aria-hidden="true"
                      ></span>
                        Chargement...
                      </b-button>
                    </div>
                  </b-col>
                </b-row>
              </form>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
      <b-col lg="6">
        <b-card no-body>
          <b-card-header class="align-items-center d-flex">
            <b-card-title class="mb-0 flex-grow-1"
            >Informations Scolaires</b-card-title
            >
          </b-card-header>

          <b-card-body>
            <form v-on:submit.prevent="submitAcademicInfos()">
              <b-row>
                <b-col md="6">
                  <div class="mb-3">
                    <label for="niveau" class="form-label"
                    >Niveau d'étude actuel <span class="text-danger">*</span></label
                    >
                    <Multiselect
                        required
                        v-model="datas.niveau"
                        :close-on-select="true"
                        :searchable="true"
                        :create-option="false"
                        placeholder="Votre niveau d'étude..."
                        :options="[
                            { value: '1ère année Baccalauréat', label: '1ère année Baccalauréat' },
                            { value: '2ème année Baccalauréat', label: '2ème année Baccalauréat' },
                            { value: 'BAC+1', label: 'BAC+1' },
                            { value: 'BAC+2', label: 'BAC+2' },
                            { value: 'BAC+3', label: 'BAC+3' },
                            { value: 'BAC+4', label: 'BAC+4' },
                            { value: 'BAC+5', label: 'BAC+5' },
                            { value: 'BAC+6', label: 'BAC+6' },
                            { value: 'Doctorant', label: 'Doctorant' },
                            { value: 'Autres', label: 'Autres' },
                            ]"
                    />
                  </div>
                </b-col>
                <b-col md="6">
                  <div class="mb-3">
                    <label for="typeEcole" class="form-label">Type d'établissement actuel</label>
                    <input
                        v-model="datas.typeEcole"
                        type="text"
                        class="form-control"
                        placeholder="Ex: Lycée, Université, Grande École..."
                        id="typeEcole"
                    />
                  </div>
                </b-col>
                <b-col md="6">
                  <div class="mb-3">
                    <label for="anneeObtentionBac" class="form-label">Année d'obtention du Bac</label>
                    <input
                        v-model="datas.anneeObtentionBac"
                        type="number"
                        class="form-control"
                        placeholder="Ex: 2022"
                        id="anneeObtentionBac"
                        min="1900"
                        :max="new Date().getFullYear() + 2"
                    />
                  </div>
                </b-col>
                <b-col md="6">
                  <div class="mb-3">
                    <label for="langue" class="form-label"
                    >Langue du Bac <span class="text-danger">*</span></label
                    >
                    <Multiselect
                        required
                        v-model="datas.langueEtude"
                        placeholder="Langue du BAC..."
                        :close-on-select="true"
                        :searchable="true"
                        :create-option="false"
                        :options="[
                            { value: 'BIOF(option Français)', label: 'BIOF(option Français)'},
                            { value: 'Option Arabe', label: 'Option Arabe' },
                            ]"
                    />
                  </div>
                </b-col>
                <b-col md="6">
                  <div class="mb-3">
                    <label for="filiere" class="form-label"
                    >Filière du Bac <span class="text-danger">*</span></label
                    >
                    <Multiselect
                        required
                        v-model="datas.filiere"
                        placeholder="Votre filière du BAC..."
                        :close-on-select="true"
                        :searchable="true"
                        :create-option="false"
                        :options="[
                            { value: 'Sciences Math A', label: 'Sciences Math A' },
                            { value: 'Sciences Math B', label: 'Sciences Math B' },
                            { value: 'Sciences Physique', label: 'Sciences Physique' },
                            { value: 'SVT', label: 'SVT' },
                            { value: 'Sciences et technologies électriques', label: 'Sciences et technologies électriques'},
                            { value: 'Sciences et technologies mécaniques', label: 'Sciences et technologies mécaniques'},
                            { value: 'Sciences économique', label: 'Sciences économique' },
                            { value: 'Sciences gestion comptable', label: 'Sciences gestion comptable'},
                            { value: 'Sciences agronomiques', label: 'Sciences agronomiques'},
                            { value: 'Lettres', label: 'Lettres' },
                            { value: 'Sciences humaines', label: 'Sciences humaines' },
                            { value: 'Sciences de la chariaa', label: 'Sciences de la chariaa'},
                            { value: 'Arts Appliqués', label: 'Arts Appliqués' },
                            ]"
                    />
                  </div>
                </b-col>
                <b-col md="6">
                  <div class="mb-3">
                    <label for="lycee" class="form-label"
                    >Nom de votre lycée/établissement</label
                    >
                    <input
                        v-model="datas.lycee"
                        type="text"
                        class="form-control"
                        placeholder="Votre établissement ici..."
                        id="lycee"
                    />
                  </div>
                </b-col>

                <b-col md="6">
                  <div class="mb-3">
                    <label for="nomTuteur" class="form-label"
                    >Nom de votre Tuteur légal (Parents ou autres)
                      <span class="text-danger">*</span></label
                    >
                    <input
                        v-model="datas.nomTuteur"
                        type="text"
                        class="form-control"
                        id="nomTuteur"
                        placeholder="Votre tuteur"
                        required
                    />
                  </div>
                </b-col>

                <b-col md="6">
                  <div class="mb-3">
                    <label for="telTuteur" class="form-label"
                    >Téléphone de votre Tuteur légal (Parents ou autres)</label
                    >
                    <input
                        v-model="datas.telTuteur"
                        type="tel"
                        class="form-control"
                        id="telTuteur"
                        placeholder="Téléphone de votre tuteur"
                        oninput="this.value = this.value.replace(/[^0-9]/g, '').substring(0, 10)"
                    />
                  </div>
                </b-col>
                <b-col lg="12">
                  <div class="d-flex justify-content-center">
                    <b-button
                        v-if="!Constraints.stepLoading"
                        type="submit"
                        variant="primary"
                    >
                      Confirmer les modifications
                    </b-button>
                    <b-button v-else variant="primary" disabled
                    ><span
                        class="spinner-border spinner-border-sm"
                        role="status"
                        aria-hidden="true"
                    ></span>
                      Chargement...
                    </b-button>
                  </div>
                </b-col>
              </b-row>
            </form>
          </b-card-body>
        </b-card>
      </b-col>
      <!-- Second column for commandes etc. -->
      <b-col lg="11">
        <!--
        <b-card no-body v-if="loggedInUser">
          <b-card-header class="align-items-center d-flex">
            <b-card-title class="mb-0 flex-grow-1">Commandes</b-card-title>
          </b-card-header>
          <b-card-body>
            <div class="live-preview">
              <div class="table-responsive table-card mb-1">
                <table class="table table-nowrap align-middle" id="orderTable" v-if="loggedInUser.commandes && loggedInUser.commandes.length > 0">
                  <thead class="text-muted table-light">
                  <tr class="text-uppercase">
                    <th class="sort" data-sort="request_id">Commande n°</th>
                    <th class="sort" data-sort="isPaid">État</th>
                    <th class="sort" data-sort="amount">Total</th>
                    <th class="sort" data-sort="payment_method">Moyen de paiement</th>
                  </tr>
                  </thead>
                  <tbody
                      class="list form-check-all"
                      v-for="(data, index) of loggedInUser.commandes"
                  :key="index"
                  >
                  <tr>
                    <td class="code_cashPlus">
                      <b v-if="data.payment_method == 'Cashplus'">{{
                          data.code_cashPlus
                        }}</b>
                      <b v-else>{{ data.request_id }}</b>
                    </td>
                    <td class="isPaid">
                      <span
                          class="badge text-uppercase"
                          :class="{
                          'bg-warning': data.isPaid == false && !data.isExpired, // Adjusted classes
                          'bg-success text-white': data.isPaid == true && !data.isExpired, // Use bg-success for paid
                          'bg-danger text-white': data.isExpired == true,
                        }"
                      >
                        <span v-if="data.isExpired">Expirée</span>
                        <span v-else>
                          <span v-if="data.isPaid">Payée</span>
                          <span v-else>En attente</span>
                        </span>
                      </span>
                    </td>
                    <td class="amount">{{ data.amount }} Dhs</td>
                    <td class="payment_method">{{ data.payment_method }}</td>
                  </tr>
                  </tbody>
                </table>
                <div
                    v-if="commandesLength === 0 && loggedInUser && !loggedInUser.isPremium"
                class="noresult"
                >
                <div class="text-center">
                  <p class="my-3">Aucune commande trouvée.</p>
                  <b-button href="/app/student/checkout" class="me-2" variant="primary">
                    <i class="ri-play-line align-bottom me-1"></i>Commander la version
                    PREMIUM
                  </b-button>
                </div>
              </div>
              <div v-else-if="!loggedInUser.commandes || loggedInUser.commandes.length === 0">
                <p class="text-center my-3">Aucune commande trouvée.</p>
              </div>
            </div>
            </div>
          </b-card-body>
        </b-card> -->


        <b-card class="mt-4" no-body> <!-- Added mt-4 for spacing -->
          <b-card-header class="d-flex justify-content-between align-items-center"> <!-- Corrected class typo -->
            <h5 class="card-title mb-0 flex-grow-1">Mot de passe</h5>
          </b-card-header>
          <b-card-body class="p-4">
            <div id="mdpChange">
              <b-form @submit.prevent="changePassword" autocomplete="off">
                <b-row>
                  <b-col lg="12">
                    <label class="form-label" for="password-input"
                    >Mot de passe actuel</label
                    >
                    <div class="position-relative auth-pass-inputgroup mb-3">
                      <input
                          :type="isActuelHided ? 'password' : 'text'"
                          v-model="pass.actuel"
                          name="password"
                          id="password-input"
                          class="form-control pe-5"
                          placeholder="Votre mot de passe actuel"
                          required
                      />
                      <button type="button"
                              class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                              @click="isActuelHided = !isActuelHided"
                      >
                        <i class="ri-eye-fill align-middle"></i>
                      </button>
                    </div>
                  </b-col>
                  <!--end col-->

                  <b-col lg="12">
                    <label class="form-label" for="new-password-input"
                    >Nouveau mot de passe</label
                    >
                    <div class="position-relative auth-pass-inputgroup mb-3">
                      <input
                          :type="isNewHided ? 'password' : 'text'"
                          v-model="pass.new"
                          name="newPassword"
                          id="new-password-input"
                          class="form-control pe-5"
                          placeholder="Nouveau mot de passe"
                          required
                      />
                      <button type="button"
                              class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                              @click="isNewHided = !isNewHided"
                      >
                        <i class="ri-eye-fill align-middle"></i>
                      </button>
                      <div class="form-text text-muted"> <!-- Changed to form-text for better semantics -->
                        Le mot de passe doit comporter au moins 8 caractères.
                      </div>
                    </div>
                  </b-col>
                  <!--end col-->

                  <b-col lg="12">
                    <label class="form-label" for="conf-password-input"
                    >Confirmation du mot de passe</label
                    >
                    <div class="position-relative auth-pass-inputgroup mb-3">
                      <input
                          :type="isConfirmHided ? 'password' : 'text'"
                          v-model="pass.conf"
                          class="form-control pe-5"
                          name="confPassword"
                          id="conf-password-input"
                          :class="{
                        'is-invalid': pass.new && pass.conf && pass.new !== pass.conf && Constraints.confBlured,
                      }"
                          @blur="Constraints.confBlured = true"
                          @focus="Constraints.confBlured = false"
                          placeholder="Confirmation du mot de passe"
                          required
                      />
                      <button type="button"
                              class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted"
                              @click="isConfirmHided = !isConfirmHided"
                      >
                        <i class="ri-eye-fill align-middle"></i>
                      </button>
                      <div v-if="pass.new && pass.conf && pass.new !== pass.conf && Constraints.confBlured" class="invalid-feedback d-block">
                        Les mots de passe ne correspondent pas.
                      </div>
                    </div>
                  </b-col>
                  <!--end col-->

                  <b-col lg="12">
                    <b-button-group class="hstack gap-2 justify-content-end">
                      <b-button
                          v-if="!Constraints.loadingPass"
                          type="submit"
                          variant="primary"
                      >
                        Confirmer
                      </b-button>
                      <b-button v-else variant="primary" disabled>
                        <b-spinner small></b-spinner> Chargement...
                      </b-button>
                    </b-button-group>
                  </b-col>
                  <!--end col-->
                </b-row>
                <!--end row-->
              </b-form>
            </div>
          </b-card-body>
        </b-card>
      </b-col>
    </b-row>
  </Layout>
</template>
<script>

//importation des bibliotheques !

import axios from "axios";
import Multiselect from "@vueform/multiselect";
import "@vueform/multiselect/themes/default.css";
import Layout from "@/layouts/main.vue";
import { mapState, mapGetters, mapActions } from "vuex"; // Import Vuex helpers

export default {
  name: "ProfileInfo",
  components: {
    Multiselect,
    Layout
  },
  data() {
    return {
      // Initialisation de data
      datas: {
        nom: null,
        prenom: null,
        ville: null,
        age: null,
        tel: null,
        email: null,
        niveau: null,
        langueEtude: null,
        filiere: null,
        typeEcole: null,
        anneeObtentionBac: null,
        lycee: null,
        nomTuteur: null,
        telTuteur: null,
        is_bac: null,
        genre:null
      },
      isActuelHided: true,
      isNewHided: true,
      isConfirmHided: true,
      isModif: false,
      flashMessage: {
        type: '',
        message: ''
      },
      pass: {
        actuel: null,
        new: null,
        conf: null,
      },
      Constraints: {
        success: false,
        error: false,
        messageError: null,
        loading: false,
        timer: null,
        emailBlured: false,
        // loading: false, // Duplicate loading property, remove one
        stepLoading: false,
        confBlured: false,
        loadingPass: false, // Added for password change loading
      },
      datasVille: [
        {
          value: null,
          label: null,
        },
      ],
    };
  },
  computed: {
    loggedInUser() {
      return this.$store.state.user.user;
    },

    ...mapGetters('user', ['isAuthenticated', 'userFullName', 'userEmail']), // Example


    commandesLength() {
      return this.loggedInUser && this.loggedInUser.commandes ? this.loggedInUser.commandes.length : 0;
    }
  },
  watch: {

    loggedInUser: {
      handler(currentUser) {
        console.log('[ProfileInfo Watcher loggedInUser] User data from store:', currentUser ? JSON.parse(JSON.stringify(currentUser)) : null);
        if (currentUser && currentUser.personalInfo && currentUser.academicInfo) {
          this.datas.nom = currentUser.personalInfo.lastName;
          this.datas.prenom = currentUser.personalInfo.firstName;
          this.datas.email = currentUser.email;
          this.datas.tel = currentUser.phoneNumber;
          this.datas.genre = currentUser.personalInfo.Sex; // You'll need a form field for this if you want to display/edit
          this.datas.age = currentUser.personalInfo.dateOfBirth; // Form 'age' field for 'dateOfBirth'

          this.datas.niveau = currentUser.academicInfo.niveauDetude;
          this.datas.filiere = currentUser.academicInfo.filiere;
          this.datas.typeEcole = currentUser.academicInfo.typeEtablissement;
          this.datas.anneeObtentionBac = currentUser.academicInfo.anneeObtentionBac;
          this.datas.lycee = currentUser.academicInfo.lycee;
          this.datas.nomTuteur = currentUser.academicInfo.nomTuteur;
          this.datas.telTuteur = currentUser.academicInfo.telTuteur;





        } else if (currentUser === null) {
          // User logged out or data fetch failed, clear relevant form fields
          this.datas.nom = null;
          this.datas.prenom = null;
          this.datas.email = null;
          this.datas.tel = null;
          this.datas.genre = null;
          this.datas.age = null;
          this.datas.niveau = null;
          this.datas.filiere = null;
          this.datas.typeEcole = null;
          this.datas.anneeObtentionBac = null;
          this.datas.lycee = null;
          this.datas.nomTuteur = null;
          this.datas.telTuteur = null;
        }
      },
      immediate: true,
      deep: true
    }
  },
  created() {
    console.log('[ProfileInfo Created] Fetching user data and city data.');
    this.$store.dispatch("user/fetchUser");
    this.getDatasVille();
  },

  methods: {
    async changePassword() {
      if (this.pass.new !== this.pass.conf) {
        this.flashMessage = {
          type: 'danger',
          message: 'Les mots de passe ne correspondent pas.'
        };
        return;
      }

      this.Constraints.loadingPass = true;

      try {
        const response = await axios.post(
            'user/api/change-password',
            {
              oldPassword: this.pass.actuel,
              newPassword: this.pass.new
            },
            {
              withCredentials: true
            }
        );

        this.flashMessage = {
          type: 'success',
          message: response.data.message || 'Mot de passe changé avec succès.'
        };

        this.pass.actuel = '';
        this.pass.new = '';
        this.pass.conf = '';
      } catch (error) {
        this.flashMessage = {
          type: 'danger',
          message: error.response?.data?.error || 'Une erreur est survenue.'
        };
      } finally {
        this.Constraints.loadingPass = false;
      }
    },


    getDatasVille() {
      axios
          .get("/api/get/villes/select")
          .then((res) => {
            this.datasVille = res.data;
          })
          .catch((err) => {
            console.error("Erreur getDatasVille:", err);
            this.$notify({
              title: "Erreur",
              text: "Impossible de charger la liste des villes.",
              type: "error",
              duration: 5000,
            });
          });
    },
    submitPersonalInfos() {
      const controller = new AbortController();
      //it allow me to cancel/abort a fetch or Axios request if it takes too long.
      this.Constraints.stepLoading = true;
      this.Constraints.loading = true;
      //Sets loading flags to true

      axios
          .post("user/api/personal-info/update", this.datas, {
            signal: controller.signal,
          })
          .then((res) => {
            this.$store.dispatch("user/fetchUser");
            //it dispatches a Vuex action user/fetchUser, to refresh the user’s data in the app's global store.
            this.$notify({
              title: "Très bien !",
              text: "Informations modifiées avec succès !",
              type: "success",
              duration: 10000,
            });
          })
          .catch((err) => {
            this.Constraints.error = true;
            const message = err.response?.data?.message || "Erreur dans le traitement de votre demande, veuillez réessayer !";
            this.Constraints.messageError = message;
            this.$notify({
              title: "Attention",
              text: message,
              type: "error",
              duration: 10000,
            });
          })
          .finally(() => {
            this.Constraints.loading = false;
            this.Constraints.stepLoading = false;
          });

      setTimeout(() => {
        if (!controller.signal.aborted) {
          controller.abort();
        }
      }, 10000); //after the 10s if the request is not compelted, it aborts the request using the AbortController.
    },
    submitAcademicInfos() {
      const controller = new AbortController();
      this.Constraints.stepLoading = true;
      this.Constraints.loading = true;


      const academicPayload = {
        niveau: this.datas.niveau,
        filiere: this.datas.filiere,
        typeEcole: this.datas.typeEcole,
        anneeObtentionBac: this.datas.anneeObtentionBac,
        lycee: this.datas.lycee,
        nomTuteur: this.datas.nomTuteur,
        telTuteur: this.datas.telTuteur,
      };

      axios
          .post("user/api/academic-info/update", academicPayload, { // NEW
            signal: controller.signal,
          })
          .then((res) => {
            this.$store.dispatch("user/fetchUser");
            this.$notify({
              title: "Très bien !",
              text: res.data.message || "Informations académiques modifiées avec succès !",
              type: "success",
              duration: 10000,
            });
          })
          .catch((err) => {
            this.Constraints.error = true;
            const message = err.response?.data?.message || err.response?.data?.error || "Erreur dans le traitement de votre demande, veuillez réessayer !";
            this.Constraints.messageError = message;
            this.$notify({
              title: "Attention",
              text: message,
              type: "error",
              duration: 10000,
            });
          })
          .finally(() => {
            this.Constraints.loading = false;
            this.Constraints.stepLoading = false;
          });

      setTimeout(() => {
        if (!controller.signal.aborted) {
          controller.abort();
        }
      }, 10000);
    }
  },
};
</script>

