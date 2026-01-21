<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Scolaire Complète - 14 Colonnes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .reste-paye { background-color: #ffdce0 !important; font-weight: bold; color: #d9534f; }
        .table-responsive { max-height: 600px; }
        thead { position: sticky; top: 0; z-index: 10; }
    </style>
</head>
<body class="bg-light">

<div class="container-fluid py-4">
    <h3 class="text-center mb-4">Saisie des Inscriptions (Conforme au Registre)</h3>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">Nouveau Renseignement</div>
        <form id="studentForm" class="card-body">
            <div class="row g-3">
                <div class="col-md-2"><input type="text" id="mat" class="form-control" placeholder="Matricule" required></div>
                <div class="col-md-3"><input type="text" id="nom" class="form-control" placeholder="Nom" required></div>
                <div class="col-md-3"><input type="text" id="prenom" class="form-control" placeholder="Prénom" required></div>
                <div class="col-md-2"><input type="number" id="insc" class="form-control" placeholder="Frais Inscription"></div>
                <div class="col-md-2"><input type="date" id="date_p" class="form-control"></div>

                <div class="col-md-2"><input type="number" id="p_tot" class="form-control" placeholder="Pension Totale (Dû)"></div>
                <div class="col-md-2"><input type="number" id="p_av" class="form-control" placeholder="Avance Pension"></div>
                <div class="col-md-2"><input type="number" id="t_tot" class="form-control" placeholder="Transport (Dû)"></div>
                <div class="col-md-2"><input type="number" id="t_av" class="form-control" placeholder="Avance Transport"></div>
                
                <div class="col-md-2"><input type="text" id="quartier" class="form-control" placeholder="Quartier / Bus"></div>
                <div class="col-md-2"><input type="text" id="contact" class="form-control" placeholder="Contact Parent"></div>
                <div class="col-md-3"><input type="text" id="recup" class="form-control" placeholder="Qui récupère l'enfant ?"></div>
                
                <div class="col-md-1">
                    <button type="submit" class="btn btn-success w-100">Ajouter</button>
                </div>
            </div>
        </form>
    </div>

    <div class="d-flex gap-2 mb-3">
        <button onclick="exportToExcel()" class="btn btn-primary">📥 Télécharger Excel (Complet)</button>
        <button onclick="resetAll()" class="btn btn-outline-danger">🔄 Effacer tout le tableau</button>
    </div>

    <div class="card shadow-sm">
        <div class="table-responsive">
            <table class="table table-bordered align-middle" id="mainTable">
                <thead class="table-dark text-center" style="font-size: 0.8rem;">
                    <tr>
                        <th>N°</th><th>Matricule</th><th>Nom</th><th>Prénom</th><th>Inscrip.</th>
                        <th>Pension (Dû)</th><th>Avance Pens.</th><th>Reste Pens.</th>
                        <th>Transp. (Dû)</th><th>Avance Trans.</th><th>Reste Trans.</th>
                        <th>Date</th><th>Quartier/Bus</th><th>Contact</th><th>Récupéré par</th>
                    </tr>
                </thead>
                <tbody id="tableBody" class="text-center"></tbody>
            </table>
        </div>
    </div>
</div>

<script>
    let students = [];

    document.getElementById('studentForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const p_tot = parseFloat(document.getElementById('p_tot').value) || 0;
        const p_av = parseFloat(document.getElementById('p_av').value) || 0;
        const t_tot = parseFloat(document.getElementById('t_tot').value) || 0;
        const t_av = parseFloat(document.getElementById('t_av').value) || 0;

        const student = {
            mat: document.getElementById('mat').value,
            nom: document.getElementById('nom').value,
            prenom: document.getElementById('prenom').value,
            insc: document.getElementById('insc').value || 0,
            p_tot: p_tot,
            p_av: p_av,
            reste_p: p_tot - p_av,
            t_tot: t_tot,
            t_av: t_av,
            reste_t: t_tot - t_av,
            date: document.getElementById('date_p').value,
            quartier: document.getElementById('quartier').value,
            contact: document.getElementById('contact').value,
            recup: document.getElementById('recup').value
        };

        students.push(student);
        updateTable();
        this.reset();
    });

    function updateTable() {
        const body = document.getElementById('tableBody');
        body.innerHTML = '';
        students.forEach((s, index) => {
            body.innerHTML += `
                <tr>
                    <td>${index + 1}</td>
                    <td>${s.mat}</td><td>${s.nom}</td><td>${s.prenom}</td><td>${s.insc}</td>
                    <td>${s.p_tot}</td><td>${s.p_av}</td><td class="${s.reste_p > 0 ? 'reste-paye' : ''}">${s.reste_p}</td>
                    <td>${s.t_tot}</td><td>${s.t_av}</td><td class="${s.reste_t > 0 ? 'reste-paye' : ''}">${s.reste_t}</td>
                    <td>${s.date}</td><td>${s.quartier}</td><td>${s.contact}</td><td>${s.recup}</td>
                </tr>`;
        });
    }

    function resetAll() { if(confirm("Tout effacer ?")) { students = []; updateTable(); } }

    function exportToExcel() {
        if(students.length === 0) return alert("Tableau vide");
        
        let html = "<table border='1'><tr><th>N°</th><th>Matricule</th><th>Nom</th><th>Prenom</th><th>Inscription</th><th>Pension Du</th><th>Avance Pension</th><th>Reste Pension</th><th>Transport Du</th><th>Avance Trans</th><th>Reste Trans</th><th>Date</th><th>Quartier</th><th>Contact</th><th>Recupere Par</th></tr>";
        
        students.forEach((s, i) => {
            html += `<tr><td>${i+1}</td><td>${s.mat}</td><td>${s.nom}</td><td>${s.prenom}</td><td>${s.insc}</td><td>${s.p_tot}</td><td>${s.p_av}</td><td>${s.reste_p}</td><td>${s.t_tot}</td><td>${s.t_av}</td><td>${s.reste_t}</td><td>${s.date}</td><td>${s.quartier}</td><td>${s.contact}</td><td>${s.recup}</td></tr>`;
        });
        html += "</table>";

        const blob = new Blob(['\ufeff' + html], { type: 'application/vnd.ms-excel' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement("a");
        a.href = url;
        a.download = "Registre_Scolaire_Complet.xls";
        a.click();
    }
</script>
</body>
</html>