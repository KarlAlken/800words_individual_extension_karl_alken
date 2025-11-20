console.log("800Words app loaded!");

const flashcardSection = document.getElementById("flashcard-section");
const cardText = document.getElementById("cardText");
const flipBtn = document.getElementById("flip-btn");
const nextBtn = document.getElementById("next-btn");

let deck = [];
let currentCard = -1;
let showingTranslation = false;

if (flashcardSection) {
  try {
    deck = JSON.parse(flashcardSection.dataset.flashcards || "[]");
  } catch (error) {
    deck = [];
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
