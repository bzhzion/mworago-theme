# Changelog

Toutes les évolutions notables du thème Mworago sont documentées ici.

Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/), versionnage
[SemVer](https://semver.org/lang/fr/). La section `[Unreleased]` accumule au fil de l'eau et
est renommée en numéro de version au moment de poser le tag.

Ce fichier est créé le 2026-09-02, longtemps après le début du projet : les évolutions antérieures
ne sont pas reconstituées, ce qui serait de la réécriture d'historique plutôt que de la
documentation. L'historique git reste la source de vérité pour ce qui précède.

## [Unreleased]

### Corrigé

- **La maquette `templates/homepage-2026.html` tirait DM Sans de Google.** Elle sert désormais la
  police depuis les fichiers **déjà embarqués dans le thème** (`assets/fonts/`), en chemins
  relatifs pour que le fichier reste ouvrable directement depuis le disque, ce qui est tout
  l'intérêt d'une maquette autonome. Rien à télécharger, rien à ajouter au CDN.
- **Précision qui compte : la production n'avait aucune fuite.** Ce fichier n'est référencé par
  aucun PHP du thème, c'est une maquette et non un gabarit servi, et `functions.php` charge déjà
  DM Sans en local avec un `preload`. La correction évite qu'on recopie le lien Google depuis cette
  maquette vers une page réelle, elle ne répare pas une fuite en ligne.

### Annulé le jour même

- Un hook `template_redirect` servant une politique de confidentialité de l'application mobile à
  `/app-confidentialite`, plus le fichier `assets/app-confidentialite.html`, ont été ajoutés puis
  **retirés dans la même journée**. Motif : **les 13 sites publient déjà une page de politique de
  confidentialité** (`politique-de-confidentialite` en français, `privacy-policy` sur les 12
  autres), vérifiée par l'API REST de chaque site.
- L'ajout créait donc un **second texte concurrent sur le même site**, ce qui est exactement le
  défaut que la démarche prétendait éviter : deux politiques côte à côte finissent par se
  contredire, et c'est la contradiction qui est indéfendable.
- La page existante est d'ailleurs bonne, et couvre le site : responsable de traitement avec
  SIREN, bases légales, AdSense avec gestionnaire de consentement, Plausible, les six droits,
  CNIL, sous-traitants. Le texte généré, lui, contenait une **erreur de fait** : il nommait
  OVHcloud comme hébergeur alors que Mworago tourne sur **Oracle Cloud**, ce que la page publiée
  indique correctement. L'erreur venait d'avoir recopié la liste de sous-traitants d'autres
  applications sans la vérifier.
- La suite est traitée là où elle doit l'être : l'application pointe vers la page **existante** de
  la langue active, et le complément propre à l'application (publicité AdMob, comptage des
  lectures depuis le téléphone, vidéos YouTube intégrées, absence de compte) est consigné comme
  action dans la fiche de registre `mworago-app.md` de `bzhzion/registre`, pour être ajouté **à la
  page existante** et non à côté.


