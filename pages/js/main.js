// JavaScript to toggle the dropdown
document.getElementById('userMenuBtn').addEventListener('click', function(e) {
  e.stopPropagation();
  this.closest('.user-menu').classList.toggle('active');
});

// Close dropdown when clicking outside
document.addEventListener('click', function(e) {
  const dropdowns = document.querySelectorAll('.user-menu');
  dropdowns.forEach(dropdown => {
    if (!dropdown.contains(e.target)) {
      dropdown.classList.remove('active');
    }
  });
});




document.addEventListener('DOMContentLoaded', function() {
    const counters = document.querySelectorAll('.number');
    const speed = 400; 
    
    counters.forEach(counter => {
      const updateCount = () => {
        const target = +counter.getAttribute('data-number');
        const count = +counter.innerText;
        const inc = target / speed;
        
        if (count < target) {
          counter.innerText = Math.ceil(count + inc);
          setTimeout(updateCount, 1);
        } else {
          counter.innerText = target.toLocaleString();
        }
      };
      
      
      const observer = new IntersectionObserver((entries) => {
        if (entries[0].isIntersecting) {
          updateCount();
          observer.unobserve(counter);
        }
      });
      
      observer.observe(counter);
    });
  });


(function() {

  var parent = document.querySelector(".range-slider");
  if(!parent) return;

  var
    rangeS = parent.querySelectorAll("input[type=range]"),
    numberS = parent.querySelectorAll("input[type=number]");

  rangeS.forEach(function(el) {
    el.oninput = function() {
      var slide1 = parseFloat(rangeS[0].value),
        	slide2 = parseFloat(rangeS[1].value);

      if (slide1 > slide2) {
				[slide1, slide2] = [slide2, slide1];
      
      }

      numberS[0].value = slide1;
      numberS[1].value = slide2;
    }
  });

  numberS.forEach(function(el) {
    el.oninput = function() {
			var number1 = parseFloat(numberS[0].value),
					number2 = parseFloat(numberS[1].value);
			
      if (number1 > number2) {
        var tmp = number1;
        numberS[0].value = number2;
        numberS[1].value = tmp;
      }

      rangeS[0].value = number1;
      rangeS[1].value = number2;

    }
  });

})();




document.addEventListener('DOMContentLoaded', function() {
  var loader = document.getElementById('ftco-loader');
  loader.classList.add('hidden');
});
  


