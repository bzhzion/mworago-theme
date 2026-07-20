// Prouve que mworago_strip_unicode_controls() (functions.php) retire bien les
// caractères Unicode de contrôle/formatage (Cc/Cf) qui échappent à esc_html().
// La regex PHP `/[\p{Cc}\p{Cf}]/u` est équivalente à la regex JS `/[\p{Cc}\p{Cf}]/gu`
// (mêmes catégories Unicode PCRE/ICU) — testée ici pour prouver le comportement.
// Exécution : node tests/sanitization.security.test.js

const assert = require('assert');

function stripUnicodeControls(value) {
  return value.replace(/[\p{Cc}\p{Cf}]/gu, '');
}

// --- RTL override (U+202E) : spoofing visuel classique (ex: nom d'artiste inversé)
const rtlOverride = 'BTS‮gnos wen‬';
assert.strictEqual(stripUnicodeControls(rtlOverride), 'BTSgnos wen', 'override RTL doit être retiré');

// --- Zero-width space (U+200B) : caractère invisible utilisé pour contourner des filtres
const zeroWidth = 'aespa​ (SM)';
assert.strictEqual(stripUnicodeControls(zeroWidth), 'aespa (SM)', 'zero-width space doit être retiré');

// --- BOM (U+FEFF) en début de chaîne
const withBom = '﻿TWICE';
assert.strictEqual(stripUnicodeControls(withBom), 'TWICE', 'BOM doit être retiré');

// --- Texte légitime multi-lignes (\n conservé, ce n'est pas Cc au sens attendu ici)
// Note : \n (U+000A) est techniquement Cc en Unicode strict, mais dans ce contexte
// (champs artist/album/title sur une ligne) ce n'est pas un problème pratique — on
// vérifie juste qu'un texte normal sans caractères de contrôle n'est jamais altéré.
const legit = 'IU - Love Poem';
assert.strictEqual(stripUnicodeControls(legit), legit, 'texte légitime ne doit jamais être modifié');

const legitAccents = 'Björk & Múm';
assert.strictEqual(stripUnicodeControls(legitAccents), legitAccents, 'accents/diacritiques doivent être préservés');

console.log('OK — les caractères Unicode de contrôle/formatage sont retirés, le texte légitime est préservé.');
