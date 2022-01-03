<template>
    <div>
        <h2 class="suggestion"><span>Oppure lasciati ispirare...</span></h2>
        <div class="flex_container">
            <!-- <div class="prev_photo">
                <i class="fas fa-chevron-left" @click="prevPhoto()"></i>
            </div>

            <div class="image_box">
                <div class="text_box">
                    <h2>{{sponsored[photoIndex].title}}</h2>
                    <h3>{{sponsored[photoIndex].city}}</h3>
                </div>

                
                <img :src="`./storage/${sponsored[photoIndex].cover}`" alt="">
                
                <div class="bullet_box">
                    <span class="bullet" v-for="(apartment, index) in sponsored" :key="index" @click="toSelectedPhoto(index)"><i class="fas fa-circle"></i></span>
                </div>

            </div>

            <div class="next_photo">
                <i class="fas fa-chevron-right" @click="nextPhoto()"></i>
            </div> -->
            <div class="hero_img ms_container-fluid">
                <div class=" ms_col-6">
                    <router-link :to="{ name: 'search', params: { slug: '&lat=41.89056&lon=12.49427&dist=10' , location: 'Roma' } }">
                    <img src="images/small-michele-bitetto-2y6ojwauKJI-unsplash.jpg" alt="Roma">
                    <div><h2 class="overlay">ROMA</h2></div> 
                    </router-link>
                    
                </div>
                <div class=" right_container ms_col-6">
                    <div class=" half_container ms_row ">
                        <div class="ms_col-6">
                            <router-link :to="{ name: 'search', params: { slug: '&lat=45.46362&lon=9.18812&dist=10' , location: 'Milano' } }">
                                <img src="images/small-ouael-ben-salah-0xe2FGo7Vc0-unsplash.jpg" alt="Milano">
                                <h2 class="overlay">MILANO</h2>
                            </router-link>   
                        </div>
                        <div class="ms_col-6">
                            <router-link :to="{ name: 'search', params: { slug: '&lat=44.49419&lon=11.34652&dist=10' , location: 'Bologna' } }">
                                <img src="images/small-bogdan-dada-vXpcpTl2Tt4-unsplash.jpg" alt="Bologna">
                                <h2 class="overlay">BOLOGNA</h2>
                            </router-link>   
                        </div>
                    </div>
                    <div class="ms_col-12 ms_row row_bottom ">
                        <router-link :to="{ name: 'search', params: { slug: '&lat=40.83998&lon=14.25254&dist=10' , location: 'Napoli' } }">
                            <img src="images/small-danilo-d-agostino-NjhHTNPkEDQ-unsplash.jpg" alt="Napoli">
                            <h2 class="overlay">NAPOLI</h2>
                        </router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'Hero',

    data() {
        return {
            photoIndex: 0,
            autoplay: ""
        }
    },

    props: {
        sponsored: Array
    },

    methods: {
        nextPhoto: function() {
            clearInterval(this.autoplay);

            if(this.photoIndex == this.sponsored.length - 1) {
                return this.photoIndex = 0;
            } else {
                return this.photoIndex++;
            }
        },

        prevPhoto: function() {
            clearInterval(this.autoplay);

            if(this.photoIndex == 0) {
                return this.photoIndex = this.sponsored.length - 1;
            } else {
                return this.photoIndex--;
            }
        },

        toSelectedPhoto: function(index) {
            clearInterval(this.autoplay);

            return this.photoIndex = index;
        }
    },

    mounted: function() {
        this.autoplay = setInterval(() => {
            if(this.photoIndex == this.sponsored.length - 1) {
                return this.photoIndex = 0;
            } else {
                return this.photoIndex++;
            }
        }, 3000);

        return this.autoplay;
    }
}
</script>

<style lang="scss" scoped>
.suggestion{
    text-align: center;
    margin:18px 0 10px 0;
    font-size:37px;
    color: #ede7e3;
    text-shadow:-4px 4px #113950;
    font-family: 'Space Mono', monospace;
    position:relative;  
    display:flex;
    justify-content: center; 
}
.suggestion::before, .suggestion::after{
    content:'';
    top:0;
    left:0;
    right:0;
    bottom:0;
    position:absolute;
}
.suggestion::before{
    background-color:#16697a;
    animation:typing 3s steps(33) forwards ;
    animation-delay: 1.5s;
}
@keyframes typing{
    to{
        left:100%;
    }
}
.hero_img{
    max-width:100vw;
    overflow:hidden;
    height:31.25rem;
    display: flex;
    margin-bottom:30px;
    .ms_col-6 {
        display:flex;
        width:100%;
        height:31.25rem;
        position:relative;
        a{
            width: 100%;
        }
        img{
            height:100%;
            width:110%;
            object-fit: cover;
            }
        .overlay{
            position:absolute;
            bottom:0;
            left:0;
            right:0;
            top:0;
            background:#489fb55b;
            color:#ffa628;
            opacity:1;
            padding:200px 0;
            font-size:5.625rem;
            text-shadow:-4px 4px #ede7e3;
            text-align:center;
            animation: appear 2s ease;
        }
        @keyframes appear {
                from { opacity: 0; }
                to { opacity: 1; }
        }
    }


    .right_container{
        display:flex;
        width:100%;
        flex-wrap: wrap;
                
        .half_container{
            display:flex;
            height:50%;
            width:100%;
                .ms_col-6{
                    height:100%;
                    width:100%;
                    position:relative;
                    img{
                        height:100%;
                        width:120%;
                        object-fit: cover;
                        display:block;
                    }
                    .overlay{
                        position:absolute;
                        bottom:0;
                        left:0;
                        right:0;
                        top:0;
                        background:#489fb55b;
                        color:#ffa628;
                        opacity:1;
                        transition: .5s ease;
                        padding:6.25 0;
                        font-size:2.5;
                        text-shadow:-4px 4px #ede7e3;
                        text-align:center;
                        animation: appear 2s ease;
                    }
                    @keyframes appear {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
                }
        }
        .ms_row{
            height:50%;
                position:relative;
                img{
                    display:block;
                        height: 15.625rem;
                        width: 170%;
                }
                    .overlay{
                        position:absolute;
                        bottom:0;
                        left:0;
                        right:0;
                        top:0;
                        background:#489fb55b;
                        color:#ffa628;
                        opacity:1;
                        transition: .5s ease;
                        padding:6.25rem 0;
                        font-size:2.5rem;
                        text-shadow:-4px 4px #ede7e3;
                        text-align:center;
                        animation: appear 2s ease;
                    }
                    @keyframes appear {
                        from { opacity: 0; }
                        to { opacity: 1; }
                    }
            }    
    }
}

// .flex_container {
//     display: flex;
//     justify-content: center;
//     align-items: center;
//     margin-bottom: 40px;

//     .prev_photo, .next_photo {
//         margin-inline: 2rem;
//     }
//     .fa-chevron-left, .fa-chevron-right{
//         font-size:20px;
//         color:#82c0cc;
//         }
//     .fas:hover{
//         cursor: pointer;
//         transform: scale(1.1);
//     }    

//     .image_box {
//         position: relative;
//         width: 820px;
//         height: 450px;
//         border-radius:10px;
//         overflow:hidden;

//         img {
//             display: block;
//             object-fit: cover;
//             width: 100%;
//             height: 100%;
//         }

//         .text_box {
//             position: absolute;
//             top: 1.2rem;
//             left: 2rem;
//             text-shadow: 0 0 4px #ede7e3 ;
//             text-transform: capitalize;
//             color:#0d4f75;
//         }

//         .bullet_box {
//             position: absolute;
//             display: flex;
//             bottom: 20px;
//             left: 50%;
//             transform: translate(-50%);
//             background-color: #11689a3b;
//             color: #0d4f75;
//             text-shadow: 0px 0px 4px #fff;
//             padding: 0.4rem;
//             border-radius: 15px;
//             border: 2px solid #0d4f75;
//             }

//         .bullet {
//             margin: 0px 8px;
//             font-size: 0.7rem;
//         }
//     }
// }

</style>