<script>
// Vous n'avez plus besoin de mapGetters pour 'hasCompletedPersonalityTest' si vous le définissez localement.
// Vous avez toujours besoin de mapState si vous l'utilisez pour 'user'.
import {mapState} from 'vuex';

export default {
  computed: {
    ...mapState('user', {
      User: state => state.user
    }),


    userLoaded() {
      const userObj = this.User;
      return userObj !== null && userObj !== undefined;
    },
    hasCompletedPersonalityTest() {
      const userObj = this.User;
      if (this.userLoaded && userObj) {
        return userObj.hasCompletedPersonalityTest === true; // Comparaison stricte pour plus de clarté
      }
      return false;
    }
  },
  created() {
    if (!this.userLoaded) {
      // console.log('[COMPONENT created] Dispatching user/fetchUser.');
      this.$store.dispatch('user/fetchUser'); // 'user' est le namespace du module
    }
  },
  watch: {
    userMappedFromStore(newUser) {
       console.log('[COMPONENT WATCH userMappedFromStore] Updated:', newUser ? JSON.parse(JSON.stringify(newUser)) : null);
    },
    hasCompletedPersonalityTest(newVal, oldVal) {
       console.log(`[COMPONENT WATCH hasCompletedPersonalityTest] Value changed from ${oldVal} to ${newVal}`);
    }
  }
};
</script>

><template>
  <div>
  <div v-if="userLoaded">
  <b-row>
    <b-col sm="3"> </b-col>
        <b-col sm="6">
          <b-card no-body>
            <b-card-body class="p-0">
              <b-alert
                  variant="primary"
                  class="border-0 rounded-0 m-0 d-flex align-items-center text-center"
                  show
              >
                <div class="text-center d-flex align-items-center">
                  Prix : <b>400Dhs !</b>
                </div>
              </b-alert>

              <b-row class="align-items-end">
                <b-col sm="12">
                  <div class="p-3">
                    🎓 Avec le <b>Pack TAWJIH+</b>, bénéficiez de :
                    <ul>
                      <li>
                        📅 Des <b>rappels personnalisés</b> sur WhatsApp pour chaque
                        ouverture d'inscription et concours des écoles supérieures.
                      </li>
                      <li>
                        📝 Un <b>suivi complet</b> de votre dossier, depuis la
                        préinscription jusqu'aux résultats.
                      </li>
                      <li>
                        🎯 Des <b>conseils d'orientation personnalisés</b> pour vous aider à
                        choisir la filière qui vous correspond le mieux.
                      </li>
                      <li>
                        📚 Des <b>informations exclusives</b> sur les
                        <b>bourses disponibles</b> au Maroc et à l'étranger.
                      </li>
                      <li>
                        💼 Un <b>accès prioritaire</b> à nos conseillers jusqu'en octobre
                        2025 pour répondre à toutes vos questions.
                      </li>
                    </ul>

                    Inscrivez-vous dès maintenant pour ne manquer aucune opportunité et
                    assurer votre avenir académique !

                    <div class="mt-3">
                      <b-link
                          href="https://wa.me/212660518125?text=SERVICE%20TAWJIH%20MAROC"
                          class="btn btn-tawjihi w-100 bouton-anime"
                      >PACK TAWJIH+</b-link
                      >
                    </div>
                  </div>
                </b-col>
              </b-row>
            </b-card-body>
          </b-card>
        </b-col>

        <b-col sm="3"> </b-col
        >
    <b-col lg="12">
      <div >
        <!-- <h5>Mon plan de réussite</h5> -->
        <div class="timeline">
          <div class="timeline-item left">
            <i class="icon mdi mdi-book-edit"></i>
            <div class="date">1ère étape</div>
            <div class="content card ribbon-box right">
              <h5>Test de Personnalité 🧠</h5>
              <p class="text-muted">
                Faites le Test de Personnalité pour nous aider à vous offrir les meilleures recommandations 🌟 en fonction de votre caractère et de vos aspirations, pour construire un avenir scolaire 🎓 et professionnel 💼 à votre image.
              </p>
              <b-row class="g-2">
                <b-col sm="12">
                  <b-button
                      v-if="hasCompletedPersonalityTest"
                      href="/personalityTest"
                      variant="primary"
                      class="w-100"
                  >
                    Modifier mes réponses <i class="ri-arrow-right-line align-middle"></i>
                  </b-button>

                  <b-button
                      v-else
                      href="/personalityTest"
                      variant="primary"
                      class="w-100 bouton-anime"
                  >
                    Commencer maintenant !
                    <i class="ri-arrow-right-line align-middle"></i>
                  </b-button>
                </b-col>
              </b-row>
            </div>
          </div>
          <div  class="timeline-item right">
            <i class="icon mdi mdi-eye"></i>
            <div class="date">2ème étape</div>
            <div class="content card ribbon-box right">
              <h5>Explorez vos résultats et recommandations 🔍</h5>
              <p class="text-muted">
                - Découvrez vos aspects de personnalité 🤹 et vos soft-skills 🌱 basés sur
                le test d’orientation. <br />
                - Consultez les secteurs de métiers et les filières recommandées 🗂 basés
                sur vos résultats.
                <br />
                - Lisez-en plus sur chaque secteur 📖 et les opportunités qu'il offre 🚀.
              </p>
              <b-row class="g-2">
                <b-col sm="12">
                  <b-button
                      href="/personalityTest/result"
                      variant="primary"
                      class="w-100 bouton-anime"
                  >Explorer les résultats
                    <i class="ri-arrow-right-line align-middle"></i
                    ></b-button>
                </b-col>
              </b-row>
            </div>
          </div>
          <div  class="timeline-item left">
            <i class="icon mdi mdi-briefcase"></i>
            <div class="date">3ème étape</div>
            <div class="content card ribbon-box right">
              <h5>Approfondissez votre connaissance 🧐</h5>
              <p class="text-muted">
                - Effectuez un Test de Compatibilité 💡 pour chaque secteur de métier qui
                vous intrigue. <br />
                - Marquez les secteurs qui vous parlent le plus comme "favoris" ❤️. Ils
                seront votre boussole 🧲!
              </p>
              <b-row class="g-2">
                <b-col sm="12">
                  <b-button
                      href="/app/student/secteurs"
                      variant="primary"
                      class="w-100 bouton-anime"
                  >Voir les secteurs et faire les tests</b-button
                  >
                </b-col>
              </b-row>
            </div>
          </div>
          <div  class="timeline-item right">
            <i class="icon mdi mdi-source-branch"></i>
            <div class="date">4ème étape</div>
            <div class="content card ribbon-box right">
              <h5>
                Explorez les filières d’études (Privé/Public) supérieures accrédités par
                l’État 🏫
              </h5>
              <p class="text-muted">
                - Cherchez 🕵️‍♂️ et filtrez les filières selon vos critères et selon nos
                recommandations basées sur les tests d’orientation. <br />
                - Ajoutez aux favoris 📌 les filières qui vous intéressent pour les
                stocker dans votre compte E-Tawjihi.
              </p>
              <b-row class="g-2">
                <b-col sm="12">
                  <b-button
                      href="/app/student/filieres"
                      variant="primary"
                      class="w-100 bouton-anime"
                  >Explorer les filières !</b-button
                  >
                </b-col>
              </b-row>
            </div>
          </div>
          <div class="timeline-item left">
            <i class="icon mdi mdi-comment-quote"></i>
            <div class="date">5ème étape</div>
            <div class="content card ribbon-box left">
              <h5>Donnez-nous votre avis 👂</h5>
              <p class="text-muted">
                Aidez-nous à vous proposer une meilleure solution 👍 grâce à vos critiques
                constructives ✍️ et vos recommandations, pour faciliter votre transition
                vers les études supérieures 🎓.
              </p>
              <b-row class="g-2">
                <b-col sm="12">
                  <b-button
                      @click="activeModal()"
                      variant="primary"
                      class="w-100 bouton-anime"
                  >Donner mon Avis !</b-button
                  >
                  <template >
                    <b-button @click="activeModal(true)" variant="primary" class="w-100"
                    >Redonner mon avis !</b-button
                    >
                  </template>
                </b-col>
              </b-row>
            </div>
          </div>

          <div  class="timeline-item right">
            <i class="icon ri-instagram-fill"></i>
            <div class="date">6ème étape</div>
            <div class="content card ribbon-box right">
              <h5>Restez engagé et curieux 📢</h5>
              <p class="text-muted">
                Suivez-nous sur Instagram 📸 pour profiter de nos conseils et rester à
                jour lors des ajouts de nouvelles fonctionnalités sur E-Tawjihi.
              </p>
              <b-row class="g-2">
                <b-col sm="12">
                  <b-button
                      type="button"
                      style="background-color: #e02c71; border-color: #e02c71"
                      class="btn-label w-100 bouton-anime"
                      target="_blank"
                      href="https://www.instagram.com/e_tawjihi_ma/"
                  ><i class="ri-instagram-line label-icon align-middle fs-16 me-2"></i>
                    Nous suivre sur instagram !</b-button
                  >
                </b-col>
              </b-row>
            </div>
          </div>
        </div>
      </div>



    </b-col>
  </b-row>
  </div>
    <div v-else>
      Chargement en cours...
    </div>
  </div>

</template>

<style scoped>

</style>