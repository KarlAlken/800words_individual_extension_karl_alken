console.log("800Words app loaded!");

const flashcardSection = document.getElementById("flashcard-section");
const cardText = document.getElementById("cardText");
const flipBtn = document.getElementById("flip-btn");
const nextBtn = document.getElementById("next-btn");
const markKnownForm = document.getElementById("mark-known-form");
const markKnownBtn = document.getElementById("mark-known-btn");

let deck = [];
let knownIds = [];
let currentCard = -1;
let showingTranslation = false;

if (flashcardSection) {
  try {
    deck = JSON.parse(flashcardSection.dataset.flashcards || "[]");
    knownIds = JSON.parse(flashcardSection.dataset.knownIds || "[]");
  } catch (error) {
    deck = [];
    knownIds = [];
    console.error("Could not parse flashcard data", error);
  }
}

const hasDeck = Array.isArray(deck) && deck.length > 0;

if (flipBtn && nextBtn && !hasDeck) {
  flipBtn.disabled = true;
  nextBtn.disabled = true;
}

function showRandomCard() {
  if (!hasDeck || !cardText) {
    return;
  }

  currentCard = Math.floor(Math.random() * deck.length);
  const card = deck[currentCard];

  cardText.textContent = card.term;
  cardText.style.color = "#000";
  flipBtn.textContent = "Show Translation";
  showingTranslation = false;

  // I update the mark known form action
  if (markKnownForm && card.id) {
    const isKnown = knownIds.includes(card.id);
    if (isKnown) {
      markKnownForm.style.display = "none";
    } else {
      markKnownForm.action = `/flashcards/${card.id}/mark-known`;
      markKnownForm.style.display = "inline-block";
    }
  }
}

function flipCard() {
  if (!hasDeck || currentCard === -1 || !cardText) {
    return;
  }

  const card = deck[currentCard];

  if (showingTranslation) {
    cardText.textContent = card.term;
    cardText.style.color = "#000";
    flipBtn.textContent = "Show Translation";
    showingTranslation = false;
  } else {
    cardText.textContent = card.translation;
    cardText.style.color = "#FF7400";
    flipBtn.textContent = "Show Word";
    showingTranslation = true;
  }
}

if (flipBtn) {
  flipBtn.addEventListener("click", flipCard);
}

if (nextBtn) {
  nextBtn.addEventListener("click", showRandomCard);
}

if (cardText && hasDeck) {
  showRandomCard();
}
