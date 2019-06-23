<template >
  <article class="message">
    <div class="message-body">
      <h1 class="title is-5">Payment Method</h1>
      <template v-if="selecting">
        <PaymentMethodSelector
         :paymentMethods = "paymentMethods"
         :selectedPaymentMethod="selectedPaymentMethod"
         @click="PaymentMethodSelected"
         />
      </template>
      <template v-else-if="creating">
        <PaymentMethodCreator
          @cancel= "creating = false"
          @added= "created"
        />
      </template>
      <template v-else>
        <template v-if="selectedPayementMethod">
          <p>{{selectedPayementMethod.card_type}}
            ending
            {{selectedPayementMethod.last_four}}</p>
          <br>
        </template>
      </template>

      <div class="filed is-gropued">
        <p class="control" v-if="paymentMethods.length">
          <a href="" class="button is-info" @click.prevent="selecting = true">Change payment method</a>
        </p>
        <p class="control">
          <a href="" class="button is-info" @click.prevent="creating = true">Add a payment method</a>
        </p>
      </div>

    </div>
  </article>
</template>

<script>
import PatmentMethodSelector from './PatmentMethodSelector'
import PatmentMethodCreator from './PatmentMethodCreator'


export default {
  data(){
      return {
        creating: false,
        selecting: false,
        localPayment: this.paymentMethods,
        selectedPayementMethod: null
      }
  },

  props: {
    paymentMethods:{
      required: true,
      type:Array
    }
  },

  watch:{
    selectedPayementMethod(paymentMethod){
      this.$emit(`input`,paymentMethod.id)
    }
  },

  methods: {
    switchPaymentMethod(PaymentMethod) {
      this.selectedPaymentMethod = PaymentMethod
    },

    PaymentMethodSelected(PaymentMethod){
      this.switchPaymentMethod(PaymentMethod)
      this.selecting = false
    },

    created(PaymentMethod) {
      this.localPaymentMethods.push(PaymentMethod)
      this.creating  = false

      this.switchPaymentMethod(PaymentMethod)
    }
  },


  computed:{
    defaultPaymentMethod(){
      return this.localPaymentMethods.find(a => a.default === true)
    }
  },

components: {
  PatmentMethodSelector,
  PatmentMethodCreator
}

  created() {
    if (this.PaymentMethods.length) {
      this.switchPaymentMethod(this.defaultPaymentMethod)

    }
  }

}

</script>
