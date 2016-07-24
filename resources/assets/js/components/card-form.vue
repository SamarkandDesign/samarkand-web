<template>
    <validator name="cardValidator">
        <form v-el:form action="{{ route }}" method="POST" novalidate>
            <slot></slot>
            <div class="row">
                <div class="form-group col-md-6 col-xs-12" v-bind:class="{ 'has-error': $cardValidator.cardnumber.touched && $cardValidator.cardnumber.invalid, 'has-success': $cardValidator.cardnumber.valid }">
                    <label for="cc-number" class="control-label">Card number</label>
                    <div class="input-group">
                        <input
                        id="cc-number"
                        type="tel"
                        class="form-control cc-number"
                        autocomplete="cc-number"
                        placeholder="•••• •••• •••• ••••"
                        v-validate:cardNumber="['required', 'card']"
                        v-model="card.number"
                        required
                        v-el:card
                        >
                        <span class="input-group-addon cc-icon-addon"><i class="cc-icon {{ cardType }}" title="{{ cardType }}"></i></span>
                    </div>
                </div>
                <div class="form-group col-md-3 col-xs-6" v-bind:class="{ 'has-error': $cardValidator.exp.touched && $cardValidator.exp.invalid, 'has-success': $cardValidator.exp.valid }">
                    <label for="cc-exp" class="control-label">Card expiry (MM/YY)</label>
                    <input
                    id="cc-exp"
                    type="tel"
                    class="form-control cc-exp"
                    autocomplete="cc-exp"
                    placeholder="•• / ••"
                    v-validate:exp="['required', 'expiry']"
                    v-model="card.exp"
                    required
                    v-el:exp
                    >
                </div>

                <div class="form-group col-md-3 col-xs-6" v-bind:class="{'has-error': $cardValidator.cvc.touched && $cardValidator.cvc.invalid, 'has-success': $cardValidator.cvc.valid }">
                    <label for="cc-cvc" class="control-label">
                        Security Code (CVV)
                        <tooltip message="3 digits on back. 4 digits on front of American Express."></tooltip>
                    </label>


                    <input
                    id="cc-cvc"
                    type="tel"
                    class="form-control cc-cvc"
                    autocomplete="off"
                    placeholder="•••"
                    v-validate:cvc="['required', 'cvc']"
                    v-model="card.cvc"
                    required
                    v-el:cvc
                    >
                </div>

            </div><!-- /row -->
            
            <p v-show="error_message" transition="fade" class="text-danger">{{ error_message }}</p>

            <div class="row">
                <div class="col-sm-4 col-md-2 col-sm-push-8 col-md-push-10">
                    <button @click.prevent="getStripeToken" class="btn btn-lg btn-success btn-block" :disabled="isLoading">
                      <span v-show="isLoading"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Loading...</span>
                      <span v-show="!isLoading">Place Order</span>
                  </button>
              </div>
              <div class="col-sm-8 col-md-10 col-sm-pull-4 col-md-pull-2">
                <p class="top-buffer"><i class="fa fa-lock"></i> Your card details are securely encrypted and handled by our payment processor. You are safe.</p>
            </div>
        </div>


    </form>
</validator>
</template>

<script>
    import payform from 'payform'
    import tooltip from './tooltip.vue'

    export default {
        props: {
            'route': {type: String, required: true},
            'stripeKey': { type: String, required: true },
            'billingName': { type: String, required: true },
            'billingAddress': { type: Object, required: true }
        },
        data () {
            return {
                card: {
                    number: '',
                    cvc: '',
                    exp: '',
                },
                error_message: null,
                isLoading: false,
            }
        },
        ready () {
            payform.cardNumberInput(this.$els.card)
            payform.expiryInput(this.$els.exp)
            payform.cvcInput(this.$els.cvc)

            window.Stripe.setPublishableKey(this.stripeKey);
        },
        methods: {
            getStripeToken () {
                this.isLoading = true;

                try {
                    // build the card object
                    let card = Object.assign({}, this.card, this.billingAddress)
                    card.name = this.billingName

                    const exp = this.expiryString
                    card.exp_year = exp.year
                    card.exp_month = exp.month

                    window.Stripe.card.createToken(card, this.stripeResponseHandler);
                } catch (e) {
                    this.handleStripeError(e);
                }
            },
            stripeResponseHandler (status, response) {
                if (response.error) {
                    this.error_message = response.error.message;
                    this.isLoading = false;

                } else {
                // response contains id and card, which contains additional card details
                let form = this.$els.form;

                let input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'stripe_token';
                input.value = response.id;

                form.appendChild(input);
                form.submit();
            }
        },
        handleStripeError (e) {
            this.isLoading = false;
            if (e.message.indexOf("expiration date") > -1) {
                this.error_message = "Your expiry date looks wrong. Please provide it in MM/YY format.";
                return;
            }
            this.error_message = "Something was wrong with your input";
        }
    },
    computed: {
        expiryString () {
            return payform.parseCardExpiry(this.card.exp)
        },
        cardType () {
            return payform.parseCardType(this.card.number) || 'none'
        }
    },
    validators: {
        card (number) {
            return payform.validateCardNumber(number)
        },
        cvc (cvc) {
            return payform.validateCardCVC(cvc, this.cardType);
        },
        expiry (expiry) {
            return payform.validateCardExpiry(payform.parseCardExpiry(expiry))
        }
    },
    components: {tooltip}
}
</script>

<style scoped>
    .cc-icon {
        display: inline-block;
        background: url('/img/cc-sprite2.css.svg');
        background-repeat: no-repeat;
        background-position: -1000px -1000px;
        white-space: nowrap;
        background-size: 30px auto;
        width:30px;
        height: 21px;
        margin-bottom: -4px;
    }
    .cc-icon-addon {
        padding: 5px;
    }
    .cc-icon.amex {
        background-position: 0 40%;
    }

    .cc-icon.visa {
        background-position: 0 0;
    }
    .cc-icon.mastercard {
        background-position: 0 20%;
    }
</style>
