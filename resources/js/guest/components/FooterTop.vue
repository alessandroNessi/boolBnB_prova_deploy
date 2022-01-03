<template>
    <div class="bg-custom ms_container-fluid">
        
            <div class="ms_row">
                <div class="links ms_col-12 ms_col-md-3">
                    <p>BoolBnb</p>
                    <ul>
                        <li>
                            <router-link :to="{ name: 'home' }">Home</router-link>
                        </li>
                        <li v-for="(link,index) in menu1" :key="index"><a :href="link.url">{{link.text}}</a></li>
                    </ul>
                </div>
                <div class="info ms_col-12 ms_col-md-3">
                    <p>Info</p>
                    <ul>
                        <li v-for="(link,index) in menu2" :key="index"><a :href="link.url">{{link.text}}</a></li>
                    </ul>
                </div>
                <div class="social ms_col-12 ms_col-md-6">
                    <h3>Seguici sui nostri social</h3>
                    <ul>
                        <li :key="index" v-for="(icon,index) in icons">
                            <a :href="icon.url"><img :src="icon.src" :key="index" alt=""></a>
                        </li>
                    </ul>
                </div>
            </div>
   
    </div>
</template>

<script>
export default {
        name: "FooterLeo",
        data(){
        return{
                menu1:[
                    // {
                    //     text:"Home",
                    //     url:"/"
                    // },
                    {
                        text:"Accedi",
                        url:"/login"
                    },
                    {
                        text:"Registrati",
                        url:"/register"
                    },
                ],
                menu2:[
                {
                    text:"Termini di utilizzo",
                    url:"https://policies.google.com/terms?hl=it"
                },
                {
                    text:"Privacy Policy",
                    url:"https://policies.google.com/privacy?hl=en-US"
                }
            ],
            icons:[
                {
                    text:"Facebook",
                    src:"images/footer-facebook.png",
                    url:"https://facebook.com"
                },
                {
                    text:"Twitter",
                    src:"images/footer-twitter.png",
                    url:"https://twitter.com"
                },
                {
                    text:"Youtube",
                    src:"images/footer-youtube.png",
                    url:"https://youtube.com"
                },
                    {
                    text:"Pinterest",
                    src:"images/footer-pinterest.png",
                    url:"https://pinterest.com"
                },
                {
                    text:"Periscope",
                    src:"images/footer-periscope.png",
                    url:"https://periscope.com"
                },
                   
            ]
            }
        }
}
</script>

<style lang="scss" scoped>

.bg-custom {
    background-color: #171819;
    padding: 50px 0px;
    color: white;
    text-align: center;
    margin-top: 4rem;
    .links, .info, .social {
        margin: 10px 0px;
    }
    .links {
        p {
            margin-bottom: 20px;
            font-weight: bold;
        }
        ul {
            list-style: none;
            li a {
                color: grey;
                display: inline-block;
                margin-bottom: 5px;
            }
        }
    }
    .info {
        p {
            margin-bottom: 10px;
            font-weight: bold;
        }
        ul {
            list-style: none;
            li a {
                color: grey;
                display: inline-block;
                margin-bottom: 5px;
            }
        }
    }
    .social {
        h3 {
            margin-bottom: 15px;
            color: #0d6efd;
        }
        ul {
            display: flex;
            justify-content: center;
            list-style: none;
            li {
                padding: 0px 15px;
            }
        }
    }
}


</style>
