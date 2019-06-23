<template >
  <article class="message">
    <div class="message-body">
      <h1 class="title is-5">Ship to</h1>
      <template v-if="selecting">
        <ShippingAddressSelector
         :addresses = "addresses"
         :selectedAddress="selectedAddress"
         @click="addressSelected"
         />
      </template>
      <template v-else-if="creating">
          <ShippingAddressCreator
            @cancel= "creating = false"
            @created= "created"
          />
      </template>
      <template v-else>
        <template v-if="selectedAddress">
          <p>{{selectedAddress.name}} <br>
          {{selectedAddress.address_1}} <br>
          {{selectedAddress.city}} <br>
          {{selectedAddress.postal_code}} <br>
          {{selectedAddress.country.name}} <br></p>
          <br>
        </template>
      </template>

      <div class="filed is-gropued">
        <p class="control">
          <a href="" class="button is-info" @click.prevent="selecting = true">Change shipping address</a>
        </p>
        <p class="control">
          <a href="" class="button is-info" @click.prevent="creating = true">Create new  shipping address</a>
        </p>
      </div>

    </div>
  </article>
</template>

<script>
import ShippingAddressSelector from './ShippingAddressSelector'
import ShippingAddressCreator from './ShippingAddressCreator'

export default {
  data(){
      return {
        creating: false,
        selecting: false,
        localAddresses: this.addresses,
        selectedAddress: null
      }
  },

  props: {
    addresses:{
      required: true,
      type:Array
    }
  },
  watch:{
    selectedAddress(address){
      this.$emit(`input`,address.id)
    }
  },
  methods: {
    switchAddress(address) {
      this.selectedAddress = address
    },
    addressSelected(address){
      this.switchAddress(address)
      this.selecting = false
    },
    created() {
      this.localAddresses.push(address)
      this.creating  = false

      this.switchAddress(address)
    }
  },


  computed:{
    defaultAddress(){
      return this.localAddresses.find(a => a.default === true)
    }
  },
  components:{
    ShippingAddressSelector,
    ShippingAddressCreator
  },

  created() {
    if (this.addresses.length) {
      this.switchAddress(this.defaultAddress)

    }
  }

}

</script>
