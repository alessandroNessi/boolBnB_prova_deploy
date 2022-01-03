<template> 

    <div class="ms_col-11 ms_col-md-6 ms_col-lg-3">
        <router-link :to="{ name: 'apartment', params: { slug: apartment.slug } }">
            <div class="card" :class="apartment.premium ? 'highlighted' : ''">
                <div v-if="apartment.cover" class="header">
                    <img :src="`http://localhost:8000/storage/${apartment.cover}`" alt="">
                </div>
                <div v-else class="header">
                    <img src="/images/image_not_found.jpg" alt="">
                </div>
                <div class="body">
                    <div class="title">
                        {{apartment.title}}
                    </div>
                    <div class="city">
                        {{apartment.city}}
                    </div>
                </div>
                <div class="footer">
                    <div class="guests_number">
                        <i class="fas fa-bed"></i>
                        <span>{{apartment.guests_number}}</span> 
                        Posti letto
                    </div>
                    <div class="bathrooms">
                        <i class="fas fa-bath"></i>
                        <span>{{apartment.bathrooms}}</span> 
                        Bagni
                    </div>
                </div>
            </div>
        </router-link>
    </div>
</template>

<script>

export default {
  name: 'Card',

  props: {
      apartment: Object
  },
//   data() {
//       return {
//           apartments: [],
//       }
//   },
//   created(){
//       axios.get('http://localhost:8000/api/apartments')
//       .then((response)=> {
//         this.apartments = response.data.data;
//       });
//   }

 mounted() {
    //  console.log(this.apartment);
     window.scrollTo(0, 0);
 }
}
</script>

<style scoped lang="scss">
    
    @import url('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css');

    .container-cards {
        padding: 25px 0px 0px 0px;
        
    }

    .card-href {
        text-decoration: none;
    }

    .card {
        position: relative;
        background-color: #ede7e3;
        transition: 0.5s;
        overflow: hidden;
        font-family: 'Raleway', sans-serif;
        border-radius: 5px;
        margin: 5px;
        &:hover {
            transform: scale(1.025);
        }

        &.highlighted::after {
            position: absolute;
            top: 0.5rem;
            right: 0.5rem;
            content: '\f005';
            font-weight: 900;
            font-family: 'Font Awesome 6 free';
            color: #ffa628;
        }
        .header {
            img {
                display: block;
                width: 100%;
                height: 150px;
                object-fit: cover;
                object-fit: center;
            }
        }
        .body {
            padding: 10px;
            .title {
                margin-block: 0.8rem;
                color: black;
                font-size: 14px;
                text-transform: uppercase;
                font-weight: bold;
                text-overflow: ellipsis;
                height: 50px;
                overflow-y: auto;
            }
            .title::-webkit-scrollbar{
                width: 0px;
            }
            .city {
                font-size: 14px;
                color:  #16697a;
                text-transform: capitalize;
                font-weight:500;
            }
        }
        .footer {
            display: flex;
            border-top: 2px solid darkgrey;
            transition: 1s;
            .bathrooms, .guests_number {
                color: #16697a;
                width: 50%;
                font-size: 12px;
                text-align: center;
                padding: 10px 5px;
                span {
                    color: black;
                    font-weight: bold;
                }
            }
        }
    } 
</style>
