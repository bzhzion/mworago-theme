# Changelog

Toutes les évolutions notables du thème Mworago sont documentées ici.

Format inspiré de [Keep a Changelog](https://keepachangelog.com/fr/1.1.0/), versionnage
[SemVer](https://semver.org/lang/fr/). La section `[Unreleased]` accumule au fil de l'eau et
est renommée en numéro de version au moment de poser le tag.

Ce fichier est créé le 2026-09-02, longtemps après le début du projet : les évolutions antérieures
ne sont pas reconstituées, ce qui serait de la réécriture d'historique plutôt que de la
documentation. L'historique git reste la source de vérité pour ce qui précède.

## [Unreleased]

### Ajouté

- **Politique de confidentialité de l'application mobile**, servie à `/app-confidentialite` depuis
  `assets/app-confidentialite.html`. Générée depuis le gabarit de parc
  (`admin/.claude/mobile-kit/politique-confidentialite.template.html`) et dérivée de la fiche de
  registre `mworago-app.md` de `bzhzion/registre`. **Ne pas la retoucher ici** : corriger le
  gabarit ou la fiche, et régénérer.
- Elle est servie par un hook `template_redirect` plutôt que par un `page-<slug>.php` ou une règle
  de réécriture, et le choix mérite l'explication. Un `page-<slug>.php` exigerait de **créer une
  page dans WordPress sur les 13 sites** en production. Un `add_rewrite_rule` ne prendrait effet
  qu'après un `wp rewrite flush`, et une URL qui répond 404 tant que personne n'a lancé cette
  commande est exactement le genre de lien mort qui fait rejeter une fiche de store.
  `template_redirect` se déclenche aussi sur une URL inconnue, avant le rendu du 404 : intercepter
  là ne demande **ni page, ni règle, ni flush**, et le fichier part avec le thème à chaque
  déploiement.
- Le fichier est cherché avec `get_theme_file_path()` et non `get_template_directory()`, pour que
  le hook continue de fonctionner si un site passe un jour par un thème enfant. S'il est absent,
  le hook **laisse le 404 normal** au lieu de servir une page vide.

> Réserve honnête sur cette version : **la syntaxe PHP n'a pas pu être vérifiée localement**, ni
> `php -l` ni Docker n'étant disponibles sur la machine, et ce dépôt n'a pas de lint PHP en CI. Le
> bloc a été relu à la main. Un `php -l functions.php` avant déploiement reste recommandé, et
> brancher ce lint en CI serait la vraie correction.
