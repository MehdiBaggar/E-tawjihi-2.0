<template>
  <Layout>
    <PageHeader :title="title" :items="items" />
    <b-row class="mt-3">
      <b-col lg="12">
        <b-card no-body class="mt-n4 mx-n4 border-0">
          <div class="bg-soft-info">
            <b-card-body class="px-4 pb-4">
              <b-row class="mb-3">
                <b-col md>
                  <b-row class="align-items-center g-3">
                    <b-col md="auto">
                      <div class="avatar-md">
                        <div class="avatar-title bg-white rounded-circle">
                          <img
                              src="../../styles/images/imported/badge-premium.png"
                              alt=""
                              class="avatar-md"
                          />
                        </div>
                      </div>
                    </b-col>
                    <b-col md>
                      <div>
                        <h4 class="fw-bold">{{ userFullName }}</h4>
                        <div class="hstack gap-3 flex-wrap">
                          <div>
                            <i class="ri-building-line align-bottom me-1"></i>
                            {{userFiliere}}
                          </div>
                          <div class="vr"></div>
                          <div>
                            <i class="ri-map-pin-2-line align-bottom me-1"></i> Maroc
                          </div>
                          <div class="vr"></div>
                          <div>
                            Membre depuis :
                            <span class="fw-medium"> {{createdAt}}</span>
                          </div>
                        </div>
                      </div>
                    </b-col>
                  </b-row>
                </b-col>
                <!-- <b-col md="auto">
                                <div class="hstack gap-1 flex-wrap mt-4 mt-md-0">
                                    <b-button type="button" variant="ghost-primary" size="sm"
                                        class="btn-icon fs-16">
                                        <i class="ri-share-line"></i>
                                    </b-button>
                                </div>
                            </b-col> -->
              </b-row>
            </b-card-body>
          </div>
        </b-card>
      </b-col>
    </b-row>

    <div class="p-md-2 p-3">
      <div v-if="loading" class="text-center py-5">
        <BSpinner variant="primary" label="Chargement..." style="width: 3rem; height: 3rem;"></BSpinner>
        <p class="mt-2 text-muted">Chargement des résultats...</p>
      </div>
      <div v-else-if="error" class="py-3">
        <BAlert show variant="danger" class="text-center">
          <i class="bx bx-error-circle me-1 align-middle fs-5"></i>
          <strong>Erreur :</strong> {{ error }}
        </BAlert>
      </div>
      <div v-else-if="!scores || Object.keys(scores).length === 0" class="py-5 text-center">
        <i class="bx bx-info-circle display-4 text-muted mb-3"></i>
        <p class="text-muted fs-5">Aucun résultat de test RIASEC n'est disponible pour le moment.</p>
        <p class="text-muted">Veuillez compléter le test pour voir vos résultats.</p>
      </div>

      <BRow v-else class="g-2"> <!-- plus petit espacement entre colonnes -->
        <BCol
            v-for="(value, trait) in topTraits"
            :key="trait"
            xl="4"
            lg="4"
            md="6"
            sm="12"
            class="mb-3"
        >
          <BCard no-body class="card-animate overflow-hidden shadow-sm border-0 h-100">
            <div class="position-absolute start-0 widget-pattern" style="z-index: 0;">
              <svg version="1.2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 120" width="200" height="120">
                <path id="Shape 8" class="s0" fill="rgba(var(--vz-primary-rgb), 0.1)"
                      d="m189.5-25.8c0 0 20.1 46.2-26.7 71.4 0 0-60 15.4-62.3 65.3-2.2 49.8-50.6 59.3-57.8 61.5-7.2 2.3-60.8 0-60.8 0l-11.9-199.4z" />
              </svg>
            </div>
            <BCardBody class="py-2 px-3" style="z-index:1;">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1 overflow-hidden">
                  <p class="text-uppercase fw-medium text-muted text-truncate mb-2">Vous êtes</p>
                  <h4 class="fs-18 fw-semibold ff-secondary mb-0">
                    {{ getFullTraitName(trait) }}
                    <span class="fs-6 text-muted">%</span>
                  </h4>
                </div>
                <div class="text-end">
                  <apexchart
                      class="apex-charts"
                      dir="ltr"
                      width="105px"
                      height="90px"
                      :series="[value]"
                      :options="{
          chart: {
            type: 'radialBar',
            sparkline: { enabled: true },
          },
          plotOptions: {
            radialBar: {
              hollow: { size: '60%' },
              dataLabels: {
                show: true,
                name: { show: false },
                value: {
                  show: true,
                  fontSize: '18px',
                  fontWeight: 600,
                  color: '#000',
                  offsetY: 5,
                  formatter: val => `${parseFloat(val).toFixed(0)}%`
                }
              }
            }
          },
          labels: ['%'],
        }"
                  />
                </div>
              </div>
            </BCardBody>
          </BCard>
        </BCol>
      </BRow>


      <!-- Donut chart pour tous les traits RIASEC -->
      <BRow v-if="scores && Object.keys(scores).length > 0" class="mt-4 justify-content-center">
        <BCol lg="10" xl="8">
          <BCard no-body class="shadow-sm border-0">
            <BCardHeader class="align-items-center d-flex py-3 bg-light bg-opacity-75 border-bottom-0">
              <BCardTitle class="mb-0 flex-grow-1 fw-semibold text-dark">
                <i class="bx bxs-pie-chart-alt-2 me-2 align-middle text-primary"></i>Votre Profil RIASEC Complet
              </BCardTitle>
            </BCardHeader>

            <BCardBody class="py-4">
              <apexchart
                  class="apex-charts"
                  dir="ltr"
                  height="380"
                  :series="chartSeries"
                  :options="chartOptions"
              />
            </BCardBody>
            <BCardFooter class="bg-light bg-opacity-50 border-top-0 pt-3 pb-3">
              <p class="small text-muted mb-0">
                Le modèle RIASEC (Holland Codes) catégorise les personnes en fonction de leur adéquation à six types de personnalité professionnels différents.
              </p>
            </BCardFooter>
          </BCard>
        </BCol>
      </BRow>

      <!-- Descriptions RIASEC (Optional Section) -->
      <BRow v-if="scores && Object.keys(scores).length > 0" class="mt-5 justify-content-center">
        <BCol lg="10" xl="8">
          <h4 class="mb-4 text-center">Comprendre Vos Traits RIASEC Dominants</h4>
          <BAccordion flush>
            <BAccordionItem
                v-for="(value, traitKey) in topTraits"
                :key="`desc-${traitKey}`"
                :title="`${getFullTraitName(traitKey)} (${value.toFixed(2)})`"
            >
              <p>{{ getTraitDescription(traitKey) }}</p>
              <p class="fw-semibold">Activités typiques :</p>
              <ul>
                <li v-for="activity in getTraitActivities(traitKey)" :key="activity">{{ activity }}</li>
              </ul>
            </BAccordionItem>
          </BAccordion>
        </BCol>
      </BRow>
    </div>
  </Layout>
</template>

<script>
import { CountTo } from "vue3-count-to";
import Layout from "@/layouts/main.vue";
import { mapGetters, mapState, mapActions } from "vuex";
import PageHeader from "@/components/page-header.vue";


// Helper function to safely get CSS variables
function getThemeColor(variableName, fallbackColor = '#808080') {
  if (typeof window !== 'undefined' && document.documentElement) {
    const color = getComputedStyle(document.documentElement).getPropertyValue(variableName.trim());
    return color ? color.trim() : fallbackColor;
  }
  return fallbackColor;
}

// RIASEC specific colors (ensure these CSS variables are defined in your theme)
const riasecColors = {
  R: { var: '--vz-success', fallback: '#28a745' },       // Realistic
  I: { var: '--vz-info', fallback: '#17a2b8' },          // Investigative
  A: { var: '--vz-warning', fallback: '#ffc107' },       // Artistic
  S: { var: '--vz-primary', fallback: '#007bff' },       // Social
  E: { var: '--vz-danger', fallback: '#dc3545' },        // Enterprising
  C: { var: '--vz-secondary', fallback: '#6c757d' },     // Conventional
};

// Map single letter keys to full names
const riasecFullNames = {
  R: "Réaliste",
  I: "Investigateur",
  A: "Artistique",
  S: "Social",
  E: "Entreprenant",
  C: "Conventionnel",
};

export default {
  page: {
    title: "Résultats",
  },
  components: {
    CountTo,
    Layout,
    PageHeader,
  },
  data() {
    return {
      maxScore: 20, // Max score per RIASEC traittitle: "Mes Résultats",
      title: "Mes Résultats",
      items: [
        {
          text: "Mon Plan de Réussite",
          href: "/app/student/mon_plan",
        },
        {
          text: "Mes Résultats",
          href: "#!",
        },
      ],
    };
  },
  computed: {
    loggedInUser() {
      return this.$store.state.user.user;
    },

    ...mapGetters('user', ['isAuthenticated', 'userFullName', 'userFiliere','createdAt']), // Example


    commandesLength() {
      return this.loggedInUser && this.loggedInUser.commandes ? this.loggedInUser.commandes.length : 0;
    },

    ...mapState('test', ['loading', 'error']),
    // Assuming scores are like { R: 15, I: 12, A: 18, S: 10, E: 16, C: 9 }
    ...mapGetters('test', ['scores']),

    topTraits() {
      if (!this.scores || Object.keys(this.scores).length === 0) return {};
      const entries = Object.entries(this.scores);
      // Sort by score descending
      entries.sort(([, scoreA], [, scoreB]) => scoreB - scoreA);
      return entries.slice(0, 3).reduce((acc, [traitKey, value]) => {
        acc[traitKey] = value;
        return acc;
      }, {});
    },

    chartSeries() {
      if (!this.scores) return [];
      // Ensure series matches the order of labels for color consistency
      return this.orderedRiasecKeys.map(key => parseFloat(this.scores[key]?.toFixed(2) || 0));
    },
    chartLabels() {
      if (!this.scores) return [];
      // Ensure labels matches the order of series for color consistency
      return this.orderedRiasecKeys.map(key => riasecFullNames[key] || key);
    },
    orderedRiasecKeys() {
      // Define a canonical order for RIASEC types
      const order = ['R', 'I', 'A', 'S', 'E', 'C'];
      // Filter keys present in scores and maintain order
      return order.filter(key => this.scores && Object.prototype.hasOwnProperty.call(this.scores, key));
    },

    chartOptions() {
      return {
        labels: this.chartLabels,
        chart: {
          height: 380,
          type: "donut",
          foreColor: getThemeColor('--vz-body-color'),
        },
        plotOptions: {
          pie: {
            donut: {
              size: '65%',
              labels: {
                show: true,
                total: {
                  show: true,
                  label: 'Profil',
                  formatter: (w) => {
                    // Could show the highest score or a summary
                    const Mapped = w.globals.seriesTotals.reduce(
                        (a, b) => a + b,
                        0
                    )
                    return Mapped.toFixed(0) // Sum of scores for example
                  }
                }
              }
            }
          }
        },
        legend: {
          position: "bottom",
          horizontalAlign: 'center',
          itemMargin: { horizontal: 8, vertical: 5 },
          labels: { colors: getThemeColor('--vz-body-color') },
          markers: { width: 10, height: 10, radius: 5 },
        },
        stroke: { show: true, width: 2, colors: [getThemeColor('--vz-card-bg', '#ffffff')] }, // Border between slices
        dataLabels: {
          enabled: true,
          formatter: function (val, opts) {
            // val is percentage here
            const score = opts.w.globals.series[opts.seriesIndex];
            return `${score.toFixed(1)}`; // Show score on slice
          },
          style: { fontSize: '11px', fontWeight: 'bold', colors: ['#FFF'] },
          dropShadow: { enabled: false },
        },
        colors: this.orderedRiasecKeys.map(key => getThemeColor(riasecColors[key]?.var, riasecColors[key]?.fallback)),
        tooltip: {
          theme: document.documentElement.getAttribute('data-bs-theme') || 'light',
          y: {
            formatter: (val) => `${val.toFixed(2)}`,
            title: {
              formatter: (seriesName) => `${seriesName}:`
            }
          }
        }
      };
    },
  },
  methods: {
    ...mapActions('test', ['loadTestResults']),
    getFullTraitName(traitKey) {
      return riasecFullNames[traitKey] || traitKey;
    },
    // Mini chart options now specific to original design
    miniChartOptions(traitKey) {
      const traitColorInfo = riasecColors[traitKey] || { var: '--vz-primary', fallback: '#3498db' };
      const color = getThemeColor(traitColorInfo.var, traitColorInfo.fallback);

      return {
        chart: {
          type: 'line', // Line chart often used for sparklines
          sparkline: {
            enabled: true,
          },
        },
        stroke: {
          curve: "smooth",
          width: 3,
          colors: [color] // Line color
        },
        fill: {
          opacity: 0.3, // Slightly less opaque for better visibility of line
          type: 'gradient',
          gradient: {
            shadeIntensity: 1,
            opacityFrom: 0.5,
            opacityTo: 0.1,
            stops: [0, 90, 100]
          },
          colors: [color] // Fill color
        },
        tooltip: {
          enabled: false,
        },
        yaxis: {
          min: 0,
          max: 100, // Percentage
        },
        xaxis: {
          labels: { show: false },
          axisBorder: { show: false },
          axisTicks: { show: false },
        },
      };
    },
    getTraitDescription(traitKey) {
      const descriptions = {
        R: "Les personnes de type Réaliste aiment les activités pratiques et les résultats concrets. Elles sont souvent douées pour travailler avec des outils, des machines, ou à l'extérieur.",
        I: "Les personnes de type Investigateur sont curieuses, analytiques et aiment résoudre des problèmes complexes. Elles excellent dans la recherche, la science et l'analyse de données.",
        A: "Les personnes de type Artistique sont créatives, expressives et originales. Elles s'épanouissent dans des environnements qui leur permettent d'innover et d'utiliser leur imagination.",
        S: "Les personnes de type Social aiment aider, enseigner et interagir avec les autres. Elles sont empathiques, coopératives et douées pour la communication.",
        E: "Les personnes de type Entreprenant sont ambitieuses, persuasives et aiment diriger. Elles sont douées pour la vente, la gestion de projets et la prise d'initiatives.",
        C: "Les personnes de type Conventionnel sont organisées, précises et aiment suivre des règles établies. Elles excellent dans les tâches administratives, la gestion de données et le respect des procédures.",
      };
      return descriptions[traitKey] || "Description non disponible.";
    },
    getTraitActivities(traitKey) {
      const activities = {
        R: ["Réparer des objets", "Travailler en plein air", "Utiliser des outils", "Construire"],
        I: ["Faire des recherches", "Analyser des données", "Résoudre des énigmes scientifiques", "Programmer"],
        A: ["Dessiner ou peindre", "Écrire de la musique ou des textes", "Jouer d'un instrument", "Concevoir"],
        S: ["Enseigner ou former", "Conseiller des personnes", "Travailler en équipe", "Soigner"],
        E: ["Vendre des produits ou services", "Diriger une équipe", "Lancer un projet", "Négocier"],
        C: ["Organiser des informations", "Gérer des budgets", "Suivre des procédures", "Travailler avec des chiffres"],
      };
      return activities[traitKey] || ["Aucune activité typique listée."];
    }
  },
  mounted() {
    this.loadTestResults();
  },
};
</script>