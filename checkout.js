// This is your test publishable API key.
const stripe = Stripe("pk_test_51LvjtlJZ8RZt5un1dPyDXsTab77g68TG81RiKojJvf0zjB4hxk761xn9mDJ7K3nMNWz2edjOGHvcjHodjTGaBiJM006OhjXGHr");

let elements;
let clientSecret;
initialize();
checkStatus();

document
  .querySelector("#payment-form")
  .addEventListener("submit", handleSubmit);


  document
  .querySelector("#email")
  .addEventListener("input", handleOnclick);
  ///WORK IN PROGRESS
  document
  .querySelector("#name")
  .addEventListener("input", handleOnclick);

function handleOnclick(e){
    ///WORK IN PROGRESS
  e.srcElement.classList.remove("inputError");
}

// Fetches a payment intent and captures the client secret
async function initialize() {

  if(!new URLSearchParams(window.location.search).has("session")){
    return;
  }
  const encodedSessionInfo = new URLSearchParams(window.location.search).get(
    "session"
  );

  const sessionInfo =  JSON.parse(atob(encodedSessionInfo));
  console.log(sessionInfo)

  const product = sessionInfo.product;
  const product_price = sessionInfo.product_price;

  const transactionInitiation = {
    product_name: sessionInfo.product,
    product_price: sessionInfo.product_price
  }

  transactionInitiation.product_name = sessionInfo.product;
  transactionInitiation.product_price = sessionInfo.product_price;

  if (!product && !product_price) {
    return;
  }
  console.log(transactionInitiation)

  var result = await fetch("/checkout_functions.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ transactionInitiation }),
  }).then((r) => r.json());

  clientSecret = result.clientSecret;

  elements = stripe.elements({ clientSecret });

  const paymentElementOptions = {
    layout: "tabs",
  };

  const paymentElement = elements.create("payment", paymentElementOptions);
  paymentElement.mount("#payment-element");
}



async function handleSubmit(e) {
  e.preventDefault();
  var emailRegex = /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;

  if(document.getElementById("email")?.value === '' || !document.getElementById("email")?.value.match(emailRegex) || document.getElementById("name")?.value === ''){
    if(document.getElementById("email")?.value === '' || !document.getElementById("email")?.value.match(emailRegex)  ){
      document.getElementById("email").classList.add('inputError');
    }
    if(document.getElementById("name")?.value === '' ){
      document.getElementById("name").classList.add('inputError');
    }
    showShortMessage("**Betaling mangler oplysninger");
    return;
  }

  setLoading(true);

  const encodedSessionInfo = new URLSearchParams(window.location.search).get(
    "session"
  );
  const sessionInfo =  JSON.parse(atob(encodedSessionInfo));

  // The items the customer wants to buy
  const transactionInitiation = {
    client_secret: clientSecret,
    name: document.getElementById("name")?.value,
    company: document.getElementById("company")?.value,
    cvr: document.getElementById("cvr")?.value,
    email: document.getElementById("email")?.value,
    product_name: sessionInfo.product, //in php checkout.file
    product_price: sessionInfo.product_price //in php checkout.file
  };

  console.log(JSON.stringify({ transactionInitiation }))

  await fetch("/checkout_functions.php", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ transactionInitiation }),
  });


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
      showMessage("Betaling Succesfuld!", "Du tages nu videre... Øjeblik :)");
        // Your application has indicated there's an error
      window.setTimeout(function(){

        // Get the current URL and query string
        var queryString = window.location.search;

        // Update the URL without reloading the page
        window.location.replace("https://localhost/checkout_success.php" + queryString);
      }, 5000);
      break;
    case "processing":
      showMessage("Betaling Processeres");
      break;
    case "requires_payment_method":
      showMessage("Betaling Mislykkedes Prøv Igen");
      break;
    default:
      showMessage("Noget Gik Galt :(");
      break;
  }
}

// ------- UI helpers -------
function showShortMessage(messageText) {
  const messageTextContainer = document.querySelector("#payment-message-text");
  if(messageTextContainer){
    messageTextContainer.textContent = messageText;
    messageTextContainer.classList.remove("hidden");
  }
}

function showMessage(messageTitle, messageText) {
  const messageTitleContainer = document.querySelector("#payment-status-title");
  const messageTextContainer = document.querySelector("#payment-status-text");

  if(messageTitleContainer){
    messageTitleContainer.textContent = messageTitle;
    messageTitleContainer.classList.remove("hidden");
  }
  if(messageTextContainer){
    messageTextContainer.textContent = messageText;
    messageTextContainer.classList.remove("hidden");
  }
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

