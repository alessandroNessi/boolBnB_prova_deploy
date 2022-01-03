document.getElementById('address').addEventListener('keyup',()=>{
  // document.getElementById('address').style.display="block";
  chiamata=document.getElementById('address').value;
  fetch('https://api.tomtom.com/search/2/geocode/'+chiamata+'.json?key=jXiFCoqvlFBNjmqBX4SuU1ehhUX1JF7t&language=it-IT')
  .then(response => response.json())
    .then(data => {
      console.log(data);
      document.getElementById('addressoptions').innerHTML="";
      for(let i=0;i<data.results.length;i++){
        document.getElementById('addressoptions').innerHTML+=`<option>${data.results[i].address.freeformAddress}</option>`;
      }
      console.log(document.getElementById('address').value);
    });
});
document.getElementById('addressoptions').addEventListener('change',()=>{
  document.getElementById('address').value=document.getElementById('addressoptions').innerHTML;
});