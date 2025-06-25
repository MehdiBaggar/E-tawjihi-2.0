<script>
import { mapState, mapGetters, mapActions } from 'vuex';
import EmojiLottie from '@/components/EmojiLottie.vue';
import heartFace from '@/styles/emojis/Heart-face.json';
import slightlyHappy from '@/styles/emojis/Slightly-happy.json';
import neutralFace from '@/styles/emojis/Neutral-face.json';
import frown from '@/styles/emojis/Frown.json';
import cry from '@/styles/emojis/Cry.json';
import angry from '@/styles/emojis/angry.json';


export default {
  name: "TestRenderer",
  components: { EmojiLottie },

  props: {
    testType: {
      type: String,
      required: true,
      default: 'Personality',
    },
  },

  data() {
    return {
      currentStep: 1,
      answers: {},
      emojis: [
        { animation: angry, value: 1, label: 'Pas du tout d\'accord' },
        { animation: frown, value: 2, label: 'Pas d\'accord' },
        { animation: neutralFace, value: 3, label: 'Neutre' },
        { animation: slightlyHappy, value: 4, label: 'D\'accord' },
        { animation: heartFace, value: 5, label: 'Tout à fait d\'accord' },
      ],

    };
  },

  computed: {
    ...mapState('test', ['loading', 'error']),
    ...mapGetters('test', ['questions', 'testTitle']),

    stepQuestions() {
      const start = (this.currentStep - 1) * 7;
      return this.questions.slice(start, start + 7);
    },

    progress() {
      return Math.round((this.currentStep / 6) * 100);
    }
  },

  methods: {
    ...mapActions('test', ['loadTest', 'submitAnswers']),

    async onSubmitAnswers() {
      try {
        const response = await this.submitAnswers({ testType: this.testType, answers: this.answers });
        this.$router.push({ name: 'test-result' });
      } catch (error) {
        alert('Erreur lors de la soumission des réponses.');
        console.error(error);
      }
    },

    nextStep() {
      if (this.currentStep < 6) this.currentStep++;
    },

    prevStep() {
      if (this.currentStep > 1) this.currentStep--;
    },

    restartTest() {
      this.answers = {};
      this.currentStep = 1;
      this.loadTest(this.testType);
    },

  },

  mounted() {
    this.loadTest(this.testType).then(() => {
      console.log("Questions chargées :", this.questions.length);

      const previousAnswers = this.$store.state.test.previousAnswers;
      console.log("Previous raw answers:", previousAnswers);

      if (previousAnswers) {
        this.answers = {};
        for (const [key, value] of Object.entries(previousAnswers)) {
          this.answers[key] = value;
        }
      }
    });
  }
};
</script>

<template>
  <div class="auth-page-wrapper auth-bg-cover py-5 d-flex justify-content-center align-items-center min-vh-100">
    <div class="bg-overlay"></div>
    <b-container>
      <b-row>
        <b-col lg="12">
          <b-card no-body class="overflow-hidden shadow-lg">
            <b-card-header class="text-center py-3 border-bottom">
              <h3 class="my-0">{{ testTitle }}</h3>
            </b-card-header>

            <b-card-body class="p-lg-5 p-4">

              <div v-if="loading" class="text-center py-5">
                <b-spinner style="width: 3rem; height: 3rem;" label="Chargement..."></b-spinner>
                <p class="mt-2">Chargement du test...</p>
              </div>

              <div v-else-if="error" class="alert alert-danger text-center my-4" role="alert">
                <h5 class="alert-heading">Erreur</h5>
                <p>{{ error }}</p>
              </div>

              <div v-else>


                <form  @submit.prevent="onSubmitAnswers" autocomplete="off">
                  <div class="mb-4">
                    <p class="text-center mb-1">Progression : {{ currentStep }} / 6</p>
                    <b-progress :value="progress" height="25px" class="shadow-sm rounded-pill">
                      <b-progress-bar :value="progress" animated striped variant="primary">
                        <strong class="text-white">{{ progress }}%</strong>
                      </b-progress-bar>
                    </b-progress>
                  </div>

                  <b-row class="gy-5">
                    <b-col
                        v-for="(question, index) in stepQuestions"
                        :key="question.id"
                        cols="12"
                        class="question-item"
                    >
                      <fieldset class="mb-3">
                        <legend class="form-label h5 text-center mb-3" :id="'question-label-' + question.id">
                          {{ (currentStep - 1) * 7 + index + 1 }}. {{ question.text }}
                        </legend>
                        <div role="radiogroup"
                             :aria-labelledby="'question-label-' + question.id"
                             class="emoji-options d-flex justify-content-around align-items-stretch text-center py-2 flex-wrap"
                             style="display: flex; margin-top: 8px;">

                          <div v-for="(emoji, idx) in emojis"
                               :key="idx"
                               @click="answers[question.id] = emoji.value"
                               @keydown.enter.prevent="answers[question.id] = emoji.value"
                               @keydown.space.prevent="answers[question.id] = emoji.value"
                               tabindex="0"
                               role="radio"
                               :aria-checked="String(answers[question.id] === emoji.value)"
                               :aria-label="emoji.label"
                               :class="[
                                 'p-2 p-md-3 rounded d-flex flex-column align-items-center justify-content-center mx-1 mb-2 emoji-selection-item',
                                 answers[question.id] === emoji.value ? 'bg-primary-subtle' : '',
                               ]"
                          >
                            <div class="emoji-container">
                              <EmojiLottie
                                  :key="`${question.id}-${emoji.value}`"
                                  :animationData="emoji.animation"
                                  :isSelected="answers[question.id] === emoji.value"
                                  :class="answers[question.id] !== undefined && answers[question.id] !== emoji.value ? 'opacity-50' : 'opacity-100'"
                              />
                            </div>
                          </div>
                        </div>
                      </fieldset>
                    </b-col>
                  </b-row>

                  <div class="d-flex justify-content-between align-items-center mt-5 pt-3 border-top">
                    <button
                        type="button"
                        class="btn btn-lg btn-outline-secondary"
                        :disabled="currentStep === 1"
                        @click="prevStep"
                    > Précédent
                    </button>

                    <div v-if="currentStep < 6">
                      <button
                          type="button"
                          class="btn btn-lg btn-primary"
                          @click="nextStep"
                      > Suivant
                      </button>
                    </div>

                    <div v-else>
                      <button
                          type="submit"
                          class="btn btn-lg btn-success"
                      > Envoyer mes réponses
                      </button>
                    </div>
                  </div>
                </form>
              </div>

            </b-card-body>
          </b-card>
        </b-col>
      </b-row>
    </b-container>
  </div>
</template>