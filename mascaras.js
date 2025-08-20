  // Máscara de telefone 
  const telefoneInput = document.getElementById('telefone');
  telefoneInput.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, ''); // remove tudo que não é número
      if (value.length > 11) value = value.slice(0, 11); 

      if (value.length > 6) {
          e.target.value = `(${value.slice(0, 2)}) ${value.slice(2, 7)}-${value.slice(7)}`;
      } else if (value.length > 2) {
          e.target.value = `(${value.slice(0, 2)}) ${value.slice(2)}`;
      } else {
          e.target.value = value;
      }
  });

  // Validação de email 
  const form = document.querySelector('form');
  form.addEventListener('submit', function(e) {
      const email = document.getElementById('email').value;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!emailRegex.test(email)) {
          alert('Por favor, insira um email válido!');
          e.preventDefault();
      }
  });