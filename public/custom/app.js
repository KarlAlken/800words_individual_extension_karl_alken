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

  // update mark known form
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

// Admin flashcards AJAX filtering
document.addEventListener("DOMContentLoaded", () => {
  const filterForm = document.getElementById("flashcards-filter-form");
  const dataContainer = document.getElementById("flashcards-data");
  const clearBtn = document.getElementById("flashcards-clear");
  const perPageDefault = (filterForm && filterForm.dataset.perPageDefault) || "20";

  if (!filterForm || !dataContainer) {
    return;
  }

  const submitFilters = (targetUrl = null) => {
    const formData = new FormData(filterForm);
    const params = new URLSearchParams(formData);
    const url = targetUrl || `${filterForm.action}?${params.toString()}`;

    fetch(url, {
      headers: {
        "Accept": "application/json",
        "X-Requested-With": "XMLHttpRequest",
      },
    })
      .then((resp) => resp.json())
      .then((data) => {
        if (data && data.html) {
          dataContainer.innerHTML = data.html;
        }
      })
      .catch(() => {
        filterForm.submit();
      });
  };

  const updateClearState = () => {
    if (!clearBtn) return;
    const lang = filterForm.querySelector('[name="language_id"]');
    const search = filterForm.querySelector('[name="search"]');
    const perPage = filterForm.querySelector('[name="per_page"]');
    const isActive =
      (lang && lang.value) ||
      (search && search.value) ||
      (perPage && perPage.value !== perPageDefault);

    if (isActive) {
      clearBtn.classList.remove("button-disabled");
      clearBtn.removeAttribute("aria-disabled");
      clearBtn.removeAttribute("tabindex");
    } else {
      clearBtn.classList.add("button-disabled");
      clearBtn.setAttribute("aria-disabled", "true");
      clearBtn.setAttribute("tabindex", "-1");
    }
  };

  filterForm.addEventListener("submit", (e) => {
    e.preventDefault();
    submitFilters();
  });

  const resetFilters = () => {
    const lang = filterForm.querySelector('[name="language_id"]');
    const search = filterForm.querySelector('[name="search"]');
    const perPage = filterForm.querySelector('[name="per_page"]');
    if (lang) lang.value = "";
    if (search) search.value = "";
    if (perPage) perPage.value = perPageDefault;
    updateClearState();
  };

  filterForm.querySelectorAll("select, input").forEach((el) => {
    el.addEventListener("change", () => {
      updateClearState();
      submitFilters();
    });
  });

  if (clearBtn) {
    clearBtn.addEventListener("click", (e) => {
      e.preventDefault();
      resetFilters();
      submitFilters(clearBtn.href);
    });
  }

  dataContainer.addEventListener("click", (e) => {
    const link = e.target.closest(".pagination a");
    if (link) {
      e.preventDefault();
      submitFilters(link.href);
    }
  });

  updateClearState();
});
