<template>
  <Vertical>

    <div class="container mt-4">
      <div class="card">
        <div class="card-body p-4 p-md-5">


          <h1 class="card-title text-center mb-4">Contrat de Service d’Inscriptions</h1>

          <form @submit.prevent="submitForm">


            <div class="form-floating mb-3">
              <input type="date" id="contract-date" v-model="formData.contractDate" class="form-control" placeholder="Date du contrat">
              <label for="contract-date">Le présent contrat a été conclu le</label>
            </div>


            <div class="mb-4 border-top pt-4">
              <h2 class="fs-5 mb-3">Informations sur le Client</h2>

              <div class="row g-3">
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" id="tutor-name" v-model="formData.tutor.name" class="form-control" placeholder="Nom du tuteur">
                    <label for="tutor-name">Tuteur</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" id="tutor-cin" v-model="formData.tutor.cin" class="form-control" placeholder="CIN du tuteur">
                    <label for="tutor-cin">CIN du tuteur</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" id="student-name" v-model="formData.student.name" class="form-control" placeholder="Nom de l'élève">
                    <label for="student-name">Élève</label>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-floating">
                    <input type="text" id="student-cin" v-model="formData.student.cin" class="form-control" placeholder="CIN de l'élève">
                    <label for="student-cin">CIN de l’élève</label>
                  </div>
                </div>
                <div class="col-12">
                  <div class="form-floating">
                    <input type="text" id="receipt-number" v-model="formData.recuNumber" class="form-control" placeholder="Numéro du reçu">
                    <label for="receipt-number">Numéro du reçu</label>
                  </div>
                </div>
              </div>
            </div>


            <div class="mb-4 border-top pt-4">
              <h2 class="fs-5 mb-3">Pack et Prix</h2>
              <div class="row">

                <div class="col-md-7 mb-3 mb-md-0">
                  <label class="form-label d-block mb-2">Pack :</label>

                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" v-model="formData.pack.name" value="TAWJIH+" id="pack-tawjıh" @change="updatePrice">
                    <label class="form-check-label" for="pack-tawjıh">
                      TAWJIH+
                    </label>
                  </div>
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" v-model="formData.pack.name" value="TASSJIL TOP15" id="pack-top15" @change="updatePrice">
                    <label class="form-check-label" for="pack-top15">
                      TASSJIL TOP 15
                    </label>
                  </div>
                  <div class="form-check mb-2">
                    <input class="form-check-input" type="radio" v-model="formData.pack.name" value="TASSJIL SCIENCE+" id="pack-science" @change="updatePrice">
                    <label class="form-check-label" for="pack-science">
                      TASSJIL SCIENCE+
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="radio" v-model="formData.pack.name" value="TASSJIL ECO+" id="pack-eco" @change="updatePrice">
                    <label class="form-check-label" for="pack-eco">
                      TASSJIL ECO+
                    </label>
                  </div>
                </div>

                <div class="col-md-5">
                  <div class="form-floating">
                    <input type="text" id="pack-price" v-model="formData.pack.price" class="form-control" placeholder="Prix" readonly>
                    <label for="pack-price">Prix du service :</label>
                  </div>
                </div>
              </div>
            </div>


            <button type="submit" class="btn btn-primary w-100 btn-lg mt-4">Générer le PDF</button>

          </form>
        </div>
      </div>
    </div>
  </Vertical>
</template>

<script>
import Vertical from "@/layouts/vertical.vue";
import { PDFDocument, rgb, StandardFonts } from "pdf-lib";

export default {
  name: "ContractForm",
  components: { Vertical },
  data() {
    return {
      formData: {
        contractDate: '',
        tutor: { name: '', cin: '' },
        student: { name: '', cin: '' },
        recuNumber: '',
        pack: { name: '', price: '' },
      }
    };
  },
  methods: {
    updatePrice() {
      const prices = {
        "TAWJIH+": "190 DHS",
        "TASSJIL TOP15": "1800 DHS",
        "TASSJIL SCIENCE+": "2300 DHS",
        "TASSJIL ECO+": "1800 DHS"
      };
      this.formData.pack.price = prices[this.formData.pack.name] || "";
    },
    async submitForm() {
      if (!this.formData.contractDate || !this.formData.tutor.name || !this.formData.student.name || !this.formData.pack.name) {
        alert("Veuillez remplir tous les champs obligatoires (Date, Tuteur, Élève, Pack).");
        return;
      }

      try {
        const existingPdfBytes = await fetch('/contrat.pdf').then(res => {
          if (!res.ok) {
            throw new Error(`Failed to fetch PDF: ${res.status} ${res.statusText}`);
          }
          return res.arrayBuffer();
        });

        const pdfDoc = await PDFDocument.load(existingPdfBytes);
        const pages = pdfDoc.getPages();
        const helveticaBoldFont = await pdfDoc.embedFont(StandardFonts.HelveticaBold);
        const textOptions = { size: 10, font: helveticaBoldFont, color: rgb(0, 0, 0) };


        if (pages.length > 0) {
          const page1 = pages[0];
          const positionsPage1 = {
            contractDate: { x: 350, y: 739.5 },
            tutorName: { x: 415, y: 701.8 },
            studentName: { x: 415, y: 685 },
            studentCIN: { x: 415, y: 670.5 },
            tutorCIN: { x: 415, y: 654 },
            recuNumber: { x: 415, y: 637 }
          };
          page1.drawText(this.formData.contractDate || '', { ...positionsPage1.contractDate, ...textOptions });
          page1.drawText(this.formData.tutor.name || '', { ...positionsPage1.tutorName, ...textOptions });
          page1.drawText(this.formData.student.name || '', { ...positionsPage1.studentName, ...textOptions });
          page1.drawText(this.formData.student.cin || '', { ...positionsPage1.studentCIN, ...textOptions });
          page1.drawText(this.formData.tutor.cin || '', { ...positionsPage1.tutorCIN, ...textOptions });
          page1.drawText(this.formData.recuNumber || '', { ...positionsPage1.recuNumber, ...textOptions });
        }


        if (pages.length > 1) {
          const page2 = pages[1];
          const positionsPage2 = {
            tutor: { x: 390, y: 120 },
            student: { x: 390, y: 101.5 },
            pack: { x: 390, y: 82.5 },
            price: { x: 390, y: 63 },
          };
          page2.drawText(this.formData.tutor.name || '', { ...positionsPage2.tutor, ...textOptions });
          page2.drawText(this.formData.student.name || '', { ...positionsPage2.student, ...textOptions });
          page2.drawText(this.formData.pack.name || '', { ...positionsPage2.pack, ...textOptions });
          page2.drawText(this.formData.pack.price || '', { ...positionsPage2.price, ...textOptions });
        }

        const pdfBytes = await pdfDoc.save();
        const blob = new Blob([pdfBytes], { type: 'application/pdf' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);
        // Generate a more specific filename
        const fileName = `Contrat_${this.formData.student.name.replace(/\s+/g, '_') || 'eleve'}_${this.formData.pack.name.replace(/\s+/g, '_') || 'pack'}.pdf`;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);

        console.log('PDF généré avec succès.');
         this.resetForm();

      } catch (error) {
        console.error('Erreur lors de la génération du PDF:', error);
        alert(`Erreur lors de la génération du PDF: ${error.message}. Assurez-vous que le fichier 'contrat.pdf' est accessible dans le dossier public.`);
      }
    },

     resetForm() {
       this.formData = {
         contractDate: '',
         tutor: { name: '', cin: '' },
         student: { name: '', cin: '' },
         recuNumber: '',
         pack: { name: '', price: '' },
       };
     }
  }
};
</script>