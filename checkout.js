// This is your test publishable API key.
const stripe = Stripe("pk_test_51LvjtlJZ8RZt5un1dPyDXsTab77g68TG81RiKojJvf0zjB4hxk761xn9mDJ7K3nMNWz2edjOGHvcjHodjTGaBiJM006OhjXGHr");

// The items the customer wants to buy
const transactionInitiation = { 
  name: "kristoffer", 
  company: "godenumre.dk", 
  cvr: "41243848", 
  email: "kristoffer.sj111@gmail.com", 
  product_name: "222-111-30", //in php checkout.file
  product_price: 599900 //in php checkout.file
};

let elements;

initialize();
checkStatus();

document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);

// Fetches a payment intent and captures the client secret
async function initialize() {


  const encodedSessionInfo = new URLSearchParams(window.location.search).get(
    "session"
  );

  const sessionInfo =  JSON.parse(atob(encodedSessionInfo));

  const product = sessionInfo.product;
  const product_price = sessionInfo.product_price;


  if (!product && !product_price) {
    return;
  }

  const { clientSecret } = await fetch("/create.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ transactionInitiation }),
  }).then((r) => r.json());

  elements = stripe.elements({ clientSecret });

  const paymentElementOptions = {
    layout: "tabs",
  };

  const paymentElement = elements.create("payment", paymentElementOptions);
  paymentElement.mount("#payment-element");
}

async function handleSubmit(e) {
  e.preventDefault();
  setLoading(true);

  const { error } = await stripe.confirmPayment({
    elements,
    confirmParams: {
      // Make sure to change this to your payment completion page
      return_url: "https://localhost/checkout.php",
      //http://localhost/checkout.html?payment_intent=pi_3M6yk3JZ8RZt5un11hGo0CgR&payment_intent_client_secret=pi_3M6yk3JZ8RZt5un11hGo0CgR_secret_eDrgl61Opp38u7OYxpXGGZ0Tp&redirect_status=succeeded
      receipt_email: document.getElementById("email").value,
    },
  });

  // This point will only be reached if there is an immediate error when
  // confirming the payment. Otherwise, your customer will be redirected to
  // your `return_url`. For some payment methods like iDEAL, your customer will
  // be redirected to an intermediate site first to authorize the payment, then
  // redirected to the `return_url`.
  if (error.type === "card_error" || error.type === "validation_error") {
    showMessage(error.message);
  } else {
    showMessage("An unexpected error occurred.");
  }

  setLoading(false);
}

// Fetches the payment intent status after payment submission
async function checkStatus() {
  const clientSecret = new URLSearchParams(window.location.search).get(
    "payment_intent_client_secret"
  );

  if (!clientSecret) {
    return;
  }

  const { paymentIntent } = await stripe.retrievePaymentIntent(clientSecret);

  switch (paymentIntent.status) {
    case "succeeded":
      showMessage("Payment succeeded!");
      break;
    case "processing":
      showMessage("Your payment is processing.");
      break;
    case "requires_payment_method":
      showMessage("Your payment was not successful, please try again.");
      break;
    default:
      showMessage("Something went wrong.");
      break;
  }
}

// ------- UI helpers -------

function showMessage(messageText) {
  const messageContainer = document.querySelector("#payment-message");

  messageContainer.classList.remove("hidden");
  messageContainer.textContent = messageText;

  setTimeout(function () {
    messageContainer.classList.add("hidden");
    messageText.textContent = "";
  }, 4000);
}

// Show a spinner on payment submission
function setLoading(isLoading) {
  if (isLoading) {
    // Disable the button and show a spinner
    document.querySelector("#submit").disabled = true;
    document.querySelector("#spinner").classList.remove("hidden");
    document.querySelector("#button-text").classList.add("hidden");
  } else {
    document.querySelector("#submit").disabled = false;
    document.querySelector("#spinner").classList.add("hidden");
    document.querySelector("#button-text").classList.remove("hidden");
  }
}