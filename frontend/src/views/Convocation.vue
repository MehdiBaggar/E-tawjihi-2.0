<template>
  <Vertical>

    <div class="container mt-4">

      <div class="card">
        <div class="card-body p-4 p-md-5">


          <h1 class="card-title text-center mb-4">Générer la Convocation</h1>

          <form @submit.prevent="submitForm">


            <h2 class="fs-5 mb-3 border-top pt-4">Informations du Candidat</h2>
            <div class="row g-3 mb-4">
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="full-name" v-model="formData.fullName" class="form-control" placeholder="Nom et prénom">
                  <label for="full-name">Nom et prénom</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="date" id="dob" v-model="formData.dateOfBirth" class="form-control" placeholder="Date de Naissance">
                  <label for="dob">Date de Naissance</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="massar-code" v-model="formData.codeMassar" class="form-control" placeholder="Code Massar">
                  <label for="massar-code">Code Massar</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="cin" v-model="formData.cin" class="form-control" placeholder="CIN">
                  <label for="cin">CIN</label>
                </div>
              </div>
            </div>


            <h2 class="fs-5 mb-3 border-top pt-4">Informations sur le Centre d'Examen</h2>
            <div class="row g-3">
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="exam-center-name" v-model="formData.examCenterName" class="form-control" placeholder="Centre du concours">
                  <label for="exam-center-name">Centre du concours</label>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-floating">
                  <input type="text" id="seat-number" v-model="formData.seatNumber" class="form-control" placeholder="Numéro de place">
                  <label for="seat-number">Numéro de place</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-floating">
                  <input type="text" id="exam-center-address" v-model="formData.examCenterAddress" class="form-control" placeholder="Adresse du centre">
                  <label for="exam-center-address">Adresse du centre</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-floating">
                  <input type="tel" id="exam-center-phone" v-model="formData.examCenterPhone" class="form-control" placeholder="Tél. du centre">
                  <label for="exam-center-phone">Tél. du centre</label>
                </div>
              </div>
            </div>


            <button type="submit" class="btn btn-primary w-100 btn-lg mt-4">Générer la Convention PDF</button>

          </form>
        </div>
      </div>
    </div>
  </Vertical>
</template>

<script>
import Vertical from "@/layouts/vertical.vue"; // Adjust path if needed
import { PDFDocument, rgb, StandardFonts } from "pdf-lib";

export default {
  name: "ConventionForm",
  components: { Vertical },
  data() {
    return {
      formData: {
        fullName: '',
        dateOfBirth: '',
        codeMassar: '',
        cin: '',
        examCenterName: '',
        seatNumber: '',
        examCenterAddress: '',
        examCenterPhone: ''
      }
    };
  },
  methods: {
    async submitForm() {



      try {

        const existingPdfBytes = await fetch('/convention.pdf').then(res => { // <--- Changed PDF name
          if (!res.ok) {
            throw new Error(`Failed to fetch PDF: ${res.status} ${res.statusText}`);
          }
          return res.arrayBuffer();
        });


        const pdfDoc = await PDFDocument.load(existingPdfBytes);
        const pages = pdfDoc.getPages();


        if (pages.length === 0) {
          throw new Error("Le fichier PDF 'convention.pdf' ne contient aucune page.");
        }
        const page = pages[0];


        const helveticaBoldFont = await pdfDoc.embedFont(StandardFonts.HelveticaBold);


        const textOptions = { size: 9, font: helveticaBoldFont, color: rgb(0, 0, 0) };

        // pour x 9dma tale3t ra9m 9dma kanzid limen
        //pour y 9dma Nazelt ra9m 9dma kanhbat l teht
        const positions = {
          fullName:         { x: 170, y: 639.2 }, // <-- EXAMPLE COORDINATES - REPLACE
          dateOfBirth:      { x: 170, y: 613 }, // <-- EXAMPLE COORDINATES - REPLACE
          codeMassar:       { x: 170, y: 588 }, // <-- EXAMPLE COORDINATES - REPLACE
          cin:              { x: 170, y: 561.8 }, // <-- EXAMPLE COORDINATES - REPLACE
          examCenterName:   { x: 420, y: 639.2 }, // <-- EXAMPLE COORDINATES - REPLACE
          seatNumber:       { x: 420, y: 613 }, // <-- EXAMPLE COORDINATES - REPLACE
          examCenterAddress:{ x: 420, y: 588 }, // <-- EXAMPLE COORDINATES - REPLACE
          examCenterPhone:  { x: 420  , y: 561.8 }, // <-- EXAMPLE COORDINATES - REPLACE
        };


        page.drawText(this.formData.fullName || '',         { ...positions.fullName, ...textOptions });
        page.drawText(this.formData.dateOfBirth || '',      { ...positions.dateOfBirth, ...textOptions });
        page.drawText(this.formData.codeMassar || '',       { ...positions.codeMassar, ...textOptions });
        page.drawText(this.formData.cin || '',              { ...positions.cin, ...textOptions });
        page.drawText(this.formData.examCenterName || '',   { ...positions.examCenterName, ...textOptions });
        page.drawText(this.formData.seatNumber || '',       { ...positions.seatNumber, ...textOptions });
        page.drawText(this.formData.examCenterAddress || '',{ ...positions.examCenterAddress, ...textOptions });
        page.drawText(this.formData.examCenterPhone || '',  { ...positions.examCenterPhone, ...textOptions });


        const pdfBytes = await pdfDoc.save();


        const blob = new Blob([pdfBytes], { type: 'application/pdf' });
        const link = document.createElement('a');
        link.href = URL.createObjectURL(blob);


        const safeFullName = this.formData.fullName.replace(/[^a-z0-9]/gi, '_').toLowerCase();
        const fileName = `Convocation${safeFullName || 'candidat'}_${this.formData.cin || 'id'}.pdf`;
        link.download = fileName;

        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        URL.revokeObjectURL(link.href);

        console.log('Convocation PDF générée avec succès.');


      } catch (error) {
        console.error('Erreur lors de la génération de la convention PDF:', error);
        alert(`Erreur lors de la génération du PDF: ${error.message}. Assurez-vous que le fichier 'convention.pdf' est accessible dans le dossier public et que les coordonnées sont correctes.`);
      }
    },


    resetForm() {
      this.formData = {
        fullName: '',
        dateOfBirth: '',
        codeMassar: '',
        cin: '',
        examCenterName: '',
        seatNumber: '',
        examCenterAddress: '',
        examCenterPhone: ''
      };
    }
  }
};
</script>

