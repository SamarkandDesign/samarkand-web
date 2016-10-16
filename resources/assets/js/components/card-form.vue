<template>
<div>
    <!-- <validator name="cardValidator"> -->
        <form ref="form" v-bind:action="route" method="POST" novalidate>
            <slot></slot>
            <div class="row">
                <div class="form-group col-md-6 col-xs-12" v-bind:class="cardValidationClasses">
                    <label for="cc-number" class="control-label">Card number</label>
                    <div class="input-group">
                        <input
                        id="cc-number"
                        type="tel"
                        class="form-control cc-number"
                        autocomplete="cc-number"
                        placeholder="•••• •••• •••• ••••"
                        validate:card-number="['required', 'card']"
                        v-model="card.number"
                        required
                        ref="card"
                        >
                        <span class="input-group-addon cc-icon-addon"><i class="cc-icon" v-bind:class="cardType" v-bind:title="cardType"></i></span>
                    </div>
                </div>
                <div class="form-group col-md-3 col-xs-6" v-bind:class="expValidationClasses">
                    <label for="cc-exp" class="control-label">Card expiry (MM/YY)</label>
                    <input
                    id="cc-exp"
                    type="tel"
                    class="form-control cc-exp"
                    autocomplete="cc-exp"
                    placeholder="•• / ••"
                    validate:exp="['required', 'expiry']"
                    v-model="card.exp"
                    required
                    ref="exp"
                    >
                </div>

                <div class="form-group col-md-3 col-xs-6" v-bind:class="cvcValidationClasses">
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
                    validate:cvc="['required', 'cvc']"
                    v-model="card.cvc"
                    required
                    ref="cvc"
                    >
                </div>

            </div><!-- /row -->
            
            <transition name="fade">
                <p v-show="error_message" class="text-danger">{{ error_message }}</p>
            </transition>

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
<!-- </validator> -->
</div>
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
        mounted () {
            this.$nextTick(() => {
                payform.cardNumberInput(this.$refs.card)
                payform.expiryInput(this.$refs.exp)
                payform.cvcInput(this.$refs.cvc)
            })

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
                let form = this.$refs.form;

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
        },

        getValidationClasses (field, validator) {
            return { 'has-error': validator[field].touched && validator[field].invalid, 'has-success': validator[field].valid }
        }
    },
    computed: {
        expiryString () {
            return payform.parseCardExpiry(this.card.exp)
        },
        cardType () {
            return payform.parseCardType(this.card.number) || 'none'
        },
        
        cardValidationClasses () {
            return ''
            return this.getValidationClasses('cardNumber', this.$cardValidator)
        },
        cvcValidationClasses () {
            return ''
            return this.getValidationClasses('cvc', this.$cardValidator)
        },
        expValidationClasses () {
            return ''
            return this.getValidationClasses('exp', this.$cardValidator)
        },
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
