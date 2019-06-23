<template >
  <table class="table is-hoverable is-fullwidth">
    <tbody>
        <CartOverviewProduct v-for="product in products"
        :key="product.id"
        :product ="product"
        />
        <tr>
          <td></td>
          <td></td>
          <td>
            <p class="has-text-wetight-bold">
              Subtotal
            </p>
          </td>
          <td> {{subtotal}}</td>
        </tr>
       <slot  name="rows"/>


    </tbody>
  </table>


</template>

<script>
   import { mapGetters} from 'vuex'

   import CartOverviewProduct from  './CartOverviewProduct'

   export default {
     computed: {
       ...mapGetters({
         products: 'cart/products',
         total: 'cart/total',
         subtotal: 'cart/subtotal',

       })
     }
   }
</script>
