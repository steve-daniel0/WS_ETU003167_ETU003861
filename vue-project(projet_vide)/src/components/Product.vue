<script>
export default {
  data() {
    return {
      products: [
        {'id': 1, 'name': 'Produit A', 'unit_price': 1, 'quantity': 5, 'description': '<b>genial</b>'},
        {'id': 2, 'name': 'Produit B', 'unit_price': 1, 'quantity': 4, 'description': '<b style="color: green;">On valide</b>'},
        {'id': 3, 'name': 'Produit C', 'unit_price': 1, 'quantity': 5, 'description': null},
        {'id': 4, 'name': 'Produit D', 'unit_price': 12, 'quantity': 8, 'description': 'sans commentaire'},
        {'id': 5, 'name': 'Produit E', 'unit_price': 4, 'quantity': 5, 'description': '<b>ok</b>'},
        {'id': 6, 'name': 'Produit F', 'unit_price': 1, 'quantity': 6, 'description': '<b>cool</b>'},
        {'id': 7, 'name': 'Produit G', 'unit_price': 1, 'quantity': 5, 'description': '<b>bof</b>'},
        {'id': 8, 'name': 'Produit H', 'unit_price': 1, 'quantity': 8, 'description': '<b>incroyable</b>'},
        {'id': 9, 'name': 'Produit I', 'unit_price': 0, 'quantity': 5, 'description': '<b>super</b>'},
        {'id': 10, 'name': 'Produit J', 'unit_price': 70, 'quantity': 5, 'description': '<u>génial</u>'},
      ]
    }
  }, 
  computed : {
        TotalProducts() {
            return this.products.length;
        }, 
        MinUnitPriceProduct() {
            return Math.min(...this.products.map(product => product.unit_price));
        }, TotalBrut() {
            let total = 0;

            this.products.forEach(p => {
              return total += p.unit_price;
            })

            return parseFloat(total.toFixed(2));
        }, TotalTVA () {
          let totalTva = this.TotalBrut * 0.2;
          return parseFloat(totalTva.toFixed(2));
          
        }, TotalHt() {
          let totalHt = this.TotalBrut + this.TotalTVA;
          return parseFloat(totalHt.toFixed(2));
        }, ResteAPayer() {
          return 100 - this.TotalHt;
        }
  }, methods : {
    isMinUnitPrice(Product) {
      return Product.unit_price == this.MinUnitPriceProduct;
    }
  }
}
</script>

<template>
  <div>    
    
    <h1 class="text-xl p-4 text-center uppercase mb-4 bg-sky-300">Nos produits</h1>
    <h1 class="text-xl p-4 text-center uppercase mb-4 bg-sky-300 text-sky-800">Nombre de produits {{ TotalProducts }}</h1>

    <p>Total Brut : {{ TotalBrut }}</p>
    <p>Total tva : {{ TotalTVA }}</p>
    <p>Total Ht : {{ TotalHt }}</p>

    <div class="container mx-auto flex flex-wrap" v-if="products.length > 0">
      <table >
        
        <thead>
          <tr>
            <td>Image</td>
            <td>Unit price</td>
            <td>Quantity</td>
            <td>Description</td>
          </tr>
        </thead>

        <tbody>
          <tr :class="product.unit_price == MinUnitPriceProduct ? 'bg-green' : 'bg-white'" class="mx-2 my-4 rounded shadow" v-for="product in products" :key="product.id">
            <td><img :src="'https://picsum.photos/140/100?random='+ product.id" class="w-full card-img-top" alt="..."/></td>
            <td>{{ product.unit_price }}</td>
            <td>{{ product.quantity }}</td>
            <td>{{ product.description }}</td>
          </tr>
        
            <tr class="bg-green">
              <td>
                    TotalBrut 
              </td>

              <td>
                    TotalTVA 
              </td>

              <td>
                   TotalHt 
              </td>

            </tr>
          </tbody>

        <tfoot>
            <tr class="bg-green" v-if="TotalBrut >= 100">
              <td>
                {{ TotalBrut }}
              </td>

              <td>
                {{ TotalTVA }}
              </td>

              <td>
                {{ TotalHt }}
              </td>
            
            </tr>
            
            <tr v-else >
              <td>
                {{ TotalBrut }}
              </td>

              <td>
                {{ TotalTVA }}
              </td>

              <td>
                {{ TotalHt }}
              </td>
            </tr>
            
        </tfoot>

      </table>
      
      <p v-if="TotalHt < 100">plus que {{ ResteAPayer }}€ pour atteindre les 100€ et avoir vos frais de livraisons offert !</p>

      <!-- <p :class="TotalBrut.is"></p> -->
    </div>
    <div v-else>
      <p>Il n'y a pas encore de produits !</p>
    </div>
  </div>
</template>

<style scoped>
.bg-green {
  background-color: #a7f3d0; /* vert clair */
}

.bg-red {
  background-color: #fecaca; /* rouge clair */
}
</style>