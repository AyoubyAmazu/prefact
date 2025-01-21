



function showTextarea1() {
   const button = $("body > .cont .data .main div .div2 .div_area1 .btn a");
   const content = $("body > .cont .data .main div .div2 .div_area1 .area1 .textarea");
   
   button.on("click", function() {
       if (content.css("display") === "block") {
           content.css("display", "none");
       } else {
           content.css("display", "block");
       }
   });
   }


   $(document).ready(function() {
       $("body > .cont > .data > .main > div > .div2 > .div_area1 > .btn > a").on("click", function() {
           const content = $("body > .cont > .data > .main > div > .div2 > .div_area1 > .area1 > .textarea");
           
           content.toggle();
       });
   });
   


function showTextarea2() {
  const button = $("body > .cont .data .main div .div2 .div_area2 .btn a");
  const content = $("body > .cont .data .main div .div2 .div_area2 .area2 .textarea");
  
  button.on("click", function() {
      if (content.css("display") === "block") {
          content.css("display", "none");
      } 
      
      else {
          content.css("display", "block");
      }
  });
  }


  $(document).ready(function() {
   $("body > .cont > .data > .main > div > .div2 > .div_area2 > .btn > a").on("click", function() {
       const content = $("body > .cont > .data > .main > div > .div2 > .div_area2 > .area2 > .textarea");
       
       content.toggle();
   });
});

function autoResize(textarea) {
    textarea.style.height = '18px';
    textarea.style.height = textarea.scrollHeight + 'px';
  }

$(document).on('input', 'body > .cont > .data > .main > div  > .div2 > .div_area2 > .area2 >  .textarea > .data > textarea', function () {
    autoResize(this);
});
$(document).on('input', 'body > .cont > .data > .main > div  > .div2 > .div_area1 > .area1 >  .textarea > .data > textarea', function () {
    autoResize(this);
}); 






