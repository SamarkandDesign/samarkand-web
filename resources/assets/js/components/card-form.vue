<template>
    <div>
        <form ref="form" v-bind:action="route" method="POST" novalidate>
            <slot></slot>
            <div class="row">
                <div class="form-group col-md-6 col-xs-12" v-bind:class="{'has-error': (formSubmitted && !cardIsValid), 'has-success': cardIsValid}">
                    <label for="cc-number" class="control-label">Card number</label>
                    <div class="input-group">
                        <input
                        id="cc-number"
                        type="tel"
                        class="form-control cc-number"
                        autocomplete="cc-number"
                        placeholder="•••• •••• •••• ••••"
                        v-model="card.number"
                        required
                        ref="card"
                        >
                        <span class="input-group-addon cc-icon-addon">
                            <span class="cc-icon-addon cc-icon-addon-container">
                                <svg :class="`svg svg-${cardType}`"><use :xlink:href="`/img/sprite.svg#svg-${cardType}`"></use></svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div class="form-group col-md-3 col-xs-6" v-bind:class="{'has-error': (formSubmitted && !expiryIsValid), 'has-success': expiryIsValid}">
                    <label for="cc-exp" class="control-label">Card expiry (MM/YY)</label>
                    <input
                    id="cc-exp"
                    type="tel"
                    class="form-control cc-exp"
                    autocomplete="cc-exp"
                    placeholder="•• / ••"
                    v-model="card.exp"
                    required
                    ref="exp"
                    >
                </div>

                <div class="form-group col-md-3 col-xs-6" v-bind:class="{'has-error': (formSubmitted && !cvcIsValid), 'has-success': cvcIsValid}">
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
                    <button @click.prevent="submitForm" class="btn btn-lg btn-success btn-block" :disabled="isLoading">
                      <span v-show="isLoading"><i class="fa fa-circle-o-notch fa-spin fa-fw"></i> Loading...</span>
                      <span v-show="!isLoading">Place Order</span>
                  </button>
              </div>
              <div class="col-sm-8 col-md-10 col-sm-pull-4 col-md-pull-2">
                <p class="top-buffer"><i class="fa fa-lock"></i> Your card details are securely encrypted and handled by our payment processor. You are safe.</p>
                <p class="accepted-cards">
                    <svg v-for="card in acceptedCards" :class="`svg svg-${card}`"><use :xlink:href="`/img/sprite.svg#svg-${card}`"></use></svg>
                </p>
            </div>
        </div>
    </form>
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
                formSubmitted: false,
                acceptedCards: ['visa', 'mastercard', 'amex'],
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
            submitForm () {
                this.formSubmitted = true
                this.getStripeToken()
            },
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
            }
        },
        computed: {
            expiryString () {
                return payform.parseCardExpiry(this.card.exp)
            },
            cardType () {
                return payform.parseCardType(this.card.number) || 'default'
            },
            cardIsValid () {
                return payform.validateCardNumber(this.card.number)
            },
            cvcIsValid () {
                return payform.validateCardCVC(this.card.cvc, this.cardType)
            },
            expiryIsValid () {
                return payform.validateCardExpiry(this.expiryString)
            },
            formIsValid () {
                return this.cardIsValid && this.cvcIsValid && this.expiryIsValid
            }
        },
        components: {tooltip}
    }
</script>

<style scoped>
    .accepted-cards {
        font-size: 32px;
    }
    .accepted-cards svg {
        margin-right: 10px;
    }
    .cc-icon-addon {
        padding: 0;
    }
    .cc-icon-addon-container {
        display: flex;
        justify-content: center;
        align-items: center;
        font-size: 33px;
    }
</style>
