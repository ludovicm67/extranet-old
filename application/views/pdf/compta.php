<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>

<h1>
  <?php
  echo $this->db->dc->getConfValueDefault('site_name', null, 'Gestion');
  ?>
  <small>Juin 2018</small>
</h1>
<style>
@page {
  margin: 0;
}

body {
  background: #000;
  margin: 0;
  font-family: sans-serif;
}

h1 {
  margin: 0;
  padding: 20px;
  color : #fff;
}

small {
  font-size: 18px;
}

table {
  background: #fff;
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #000;
  color: #fff;
}

table, td {
  border: 1px solid #000;
}

th {
  border: 1px solid #fff;
}

th, td {
  padding: 5px;
}
</style>
<table>
  <thead>
    <tr>
      <th>Nom</th>
      <th>Contrat</th>
      <th>Heures sup</th>
      <th>Congés</th>
      <th>Maladie</th>
      <th>Autres abs.</th>
      <th>Transports</th>
      <th>Dépenses</th>
      <th>Observations</th>
    </tr>
  </thead>
  <tbody>
    <?php for ($i = 0; $i < 100; $i++): ?>
    <tr>
      <?php $prenoms = ['Ludovic', 'Thomas', 'Marc', 'Luc', 'John', 'Gérard', 'Christian', 'Léa', 'Justine', 'Camille']; $noms = ['Dupond', 'De Super-Loin', 'Muller', 'Doe', 'Dupont', 'Test']; shuffle($prenoms); shuffle($noms); $prenom = $prenoms[0]; $nom = $noms[0];  $contrats = ['CDD', 'CDI', 'Stage', 'Contrat pro', 'Apprentissage']; shuffle($contrats); $contrat = $contrats[0]; $conges = round((rand(0, 14 * 10) / 10) * 2) / 2; $maladie = round((rand(0, 3 * 10) / 10) * 2) / 2; $autre = round((rand(0, 2 * 10) / 10) * 2) / 2; $observation = ''; if ($contrat == 'Stage') { $observation = rand(14, 25) . ' jours de présence'; } else { if (rand(0, 4) == 0) { switch (round(0, 3)) { case 0: $observation = 'congés'; break; case 1: $observation = 'maladie'; break; case 2: $observation = 'autre'; break; } $observation .= ' du ' . rand(1, 10) . '/06 après-midi au ' . rand(18, 30) . '/06 matin'; if (rand(0, 2) == 0) { switch (round(0, 3)) { case 0: $observation .= ' et congés'; break; case 1: $observation .= ' et maladie'; break; case 2: $observation .= ' et autre'; break; } $observation .= ' du ' . rand(1, 10) . ' au ' . rand(18, 30); } } }  ?>
      <td><?php echo $prenom . ' ' . $nom; ?></td>
      <td><?php echo $contrat; ?></td>
      <td><?php echo number_format(round(rand(0, 14) * 2) / 2, 2, ',', ' '); ?> h</td>
      <td><?php echo number_format($conges, 1, ',', ' '); ?> jour<?php echo ($conges > 1) ? 's' : ''; ?></td>
      <td><?php echo number_format($maladie, 1, ',', ' '); ?> jour<?php echo ($maladie > 1) ? 's' : ''; ?></td>
      <td><?php echo number_format($autre, 1, ',', ' '); ?> jour<?php echo ($autre > 1) ? 's' : ''; ?></td>
      <td><?php echo number_format(rand(0, 142 * 100) / 100, 2, ',', ' '); ?> €</td>
      <td><?php echo number_format(rand(0, 4242 * 100) / 100, 2, ',', ' '); ?> €</td>
      <td><?php echo $observation; ?></td>
    </tr>
    <?php endfor; ?>
  </tbody>
</table>
