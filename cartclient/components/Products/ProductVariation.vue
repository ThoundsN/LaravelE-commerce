<template >
  <div class="field">
<label class="label">
 {{type }}
</label>
<div class="control">
  <div class="select is-fullwidth">
    <select  :value="selectedVariationId" @change="changed($event,type)">
      <option value="">Please choose</option>
      <option v-for="variation in variations" :key="variation.id"
      :value="variation.id"
      :disabled='!variation.in_stock'
      >
        {{variation.name}}
        <template v-if="variation.price_varies">
          {{variation.price}}
        </template>
        <template v-if="!variation.in_stock">
          (Out of Stocks)
        </template>
      </option>
    </select>
  </div>
</div>
  </div>


</template>

<script>
  export default{
    props:{
      type:{
        required:true,
        type:String
      },
      variations:{
        required:true,
        type:Array
      },
      value:{
        required: false,
        default:''
      }
    },

    computed:{
      selectedVariationId(){
        if (!this.findvariations(this.value.id)) {
          return ''
        }
        return this.value.id
      }
    },
    methods: {
      changed(event,type) {
        this.$emit('input',this.findvariations(event.target.value))
      },
      findvariations(id){
        let variation = this.variations.find(v => v.id ==id)

        if (typeof variation === 'undefined') {
          return null

        }
        return variation
      }
    }
  }
</script>
