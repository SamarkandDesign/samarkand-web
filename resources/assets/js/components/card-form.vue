<template>
    <div class="row">
        <validator name="cardValidator">
            <form v-el:form action="{{ route }}" method="post" novalidate>
                <slot></slot>
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
                <label for="cc-cvc" class="control-label">Security Code (CVV) <i class="fa fa-question-circle" title="3 digits on back. 4 digits on front of American Express."></i></label>

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

            <p v-if="error_message" class="text-danger col-md-12">{{ error_message }}</p>

            <div class="col-sm-4 col-md-2 col-sm-push-8 col-md-push-10">
                <button @click.prevent="getStripeToken" class="btn btn-lg btn-success btn-block" :disabled="isLoading">Place Order</button>
            </div>
            <div class="col-sm-8 col-md-10  col-sm-pull-4 col-md-pull-2">
                <p class="top-buffer"><i class="fa fa-lock"></i> Your card details are securely encrypted and handled by our payment processor. You are safe.</p>
            </div>

        </form>
    </validator>
</div>
</template>

<script>
import payform from 'payform'

export default {
    props: {
        'route': {type: String, required: true},
        'stripeKey': { type: String, required: true },
        'billingName': { type: String, required: true },
        'billingAddress': { type: Object, required: true }
    },
    data: function () {
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
    ready: function() {
        payform.cardNumberInput(this.$els.card)
        payform.expiryInput(this.$els.exp)
        payform.cvcInput(this.$els.cvc)

        window.Stripe.setPublishableKey(this.stripeKey);
    },
    methods: {
        getStripeToken: function() {
            this.isLoading = true;

            try {
                // build the card object
                let card = Object.assign(Object.assign({}, this.card), this.billingAddress)
                card.name = this.billingName

                const exp = this.expiryString
                card.exp_year = exp.year
                card.exp_month = exp.month

                window.Stripe.card.createToken(card, this.stripeResponseHandler);
            } catch (e) {
                this.handleStripeError(e);
            }
        },
        stripeResponseHandler: function(status, response) {
            if (response.error) {
                this.error_message = response.error.message;
                this.isLoading = false;

            } else {
                // response contains id and card, which contains additional card details
                var form = this.$els.form;

                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'stripe_token';
                input.value = response.id;

                form.appendChild(input);
                form.submit();
            }
            console.log(status, response);
        },
        handleStripeError: function(e) {
            console.log(e)
            this.isLoading = false;
            if (e.message.indexOf("expiration date") > -1) {
                this.error_message = "Your expiry date looks wrong. Please provide it in MM/YY format.";
                return;
            }
            this.error_message = "Something was wrong with your input";
        }
    },
    computed: {
        expiryString: function () {
            return payform.parseCardExpiry(this.card.exp)
        },
        cardType: function() {
            return payform.parseCardType(this.card.number) || 'none'
        }
    },
    validators: {
        card: function(number) {
            return payform.validateCardNumber(number)
        },
        cvc: function(cvc) {
            return payform.validateCardCVC(cvc, this.cardType);
        },
        expiry: function(expiry) {
            return payform.validateCardExpiry(payform.parseCardExpiry(expiry))
        }
    }
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
