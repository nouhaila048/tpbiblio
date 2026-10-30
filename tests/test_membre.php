
<?php

// Tests pour la classe Membre - Hotfix v1.0.1

// Test : creation d'un membre
$m = new Membre(1, 'Alice');

verifier($m->getId() === 1, 'getId() retourne le bon id');
verifier($m->getNom() === 'Alice', 'getNom() retourne le bon nom');
verifier(count($m->getEmprunts()) === 0, 'Nouveau membre : aucun emprunt');

// Test : premier emprunt
$livre1 = new Livre('9782100545261', 'Algo', 'Cormen');
$m->emprunter($livre1);

verifier(count($m->getEmprunts()) === 1, 'Apres 1 emprunt : 1 livre');
verifier(!$livre1->estDisponible(), 'Le livre est indisponible apres emprunt par le membre');

// Test : deuxieme emprunt
$livre2 = new Livre('9780132350884', 'Clean Code', 'Martin');
$m->emprunter($livre2);

verifier(count($m->getEmprunts()) === 2, 'Apres 2 emprunts : 2 livres');

// Test : troisieme emprunt
$livre3 = new Livre('9780201633610', 'Patterns', 'Gamma');
$m->emprunter($livre3);

verifier(count($m->getEmprunts()) === 3, 'Apres 3 emprunts : 3 livres');

// Test : quatrieme emprunt autorise
$livre4 = new Livre('9780596007126', 'UML', 'Fowler');
$m->emprunter($livre4);

verifier(count($m->getEmprunts()) === 4, 'Quatrieme emprunt autorise : 4 livres');
verifier(!$livre4->estDisponible(), 'Le quatrieme livre est indisponible apres emprunt');

// Test : cinquieme emprunt autorise
$livre5 = new Livre('9780131103627', 'Langage C', 'Kernighan');
$m->emprunter($livre5);

verifier(count($m->getEmprunts()) === 5, 'Cinquieme emprunt autorise : limite atteinte');
verifier(!$livre5->estDisponible(), 'Le cinquieme livre est indisponible apres emprunt');

// Test : sixieme emprunt refuse
$livre6 = new Livre('9780321563842', 'Test', 'Auteur');

$exceptionLimite = false;

try {
    $m->emprunter($livre6);
} catch (Exception $e) {
    $exceptionLimite = true;
}

verifier($exceptionLimite, 'Emprunter un 6eme livre leve une exception (limite 5)');
verifier($livre6->estDisponible(), 'Le 6eme livre reste disponible apres exception');
verifier(count($m->getEmprunts()) === 5, 'Apres refus du 6eme emprunt : toujours 5 livres');

// Test : rendre un livre
$m->rendre($livre1);

verifier(count($m->getEmprunts()) === 4, 'Apres rendu : 4 emprunts');
verifier($livre1->estDisponible(), 'Le livre rendu est de nouveau disponible');

// Test : rendre un livre non emprunte
$livreExt = new Livre('9780134685991', 'Java', 'Bloch');

$exceptionNonEmprunte = false;

try {
    $m->rendre($livreExt);
} catch (Exception $e) {
    $exceptionNonEmprunte = true;
}

verifier($exceptionNonEmprunte, 'Rendre un livre non emprunte leve une exception');

// Test : apres rendu, on peut re-emprunter
$m->emprunter($livre1);

verifier(count($m->getEmprunts()) === 5, 'Apres re-emprunt : 5 livres');
