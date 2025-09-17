document.addEventListener('DOMContentLoaded', () => {
    const swiper = new Swiper('.swiper', {
        direction: 'horizontal',
        grabcursor: true,
        slidesPerView: 1.5,
        spacebetween: 10,
        loop: true,
        speed: 1000,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },
        // autoplay: {
        //   delay: 2500,
        //   disableOnInteraction: false,
        // },
        
        navigation: {
            nextEl: '.next-button',
            prevEl: '.prev-button',
        },
      });


    const products = document.querySelectorAll('.product-container .product');
  
    function calculateProductsPerRow() {
      const firstProduct = products[0];
      if (!firstProduct) 
        return 4; 
      
      const productWidth = firstProduct.offsetWidth;
      const containerWidth = document.querySelector('.product-container').offsetWidth;

      return Math.floor(containerWidth / productWidth);
    }

    function showInitialProducts() {
      const productsPerRow = calculateProductsPerRow();

      products.forEach((product, index) => {
        if (index >= productsPerRow) {
          product.style.display = 'none';
        } else {
          product.style.display = 'block';
        }
      });
    }

    function showAllProducts() {
      products.forEach(product => {
        product.style.display = 'block';
      });
    }

    function initializeProductDisplay() {
      showInitialProducts();
    }

    const showMoreBtn = document.querySelector('.home-show-more-btn');
    if (showMoreBtn) {
      showMoreBtn.textContent = 'Show More';
      let expanded = false;
      
      showMoreBtn.addEventListener('click', function() {
        if (expanded) {
          showInitialProducts();
          this.textContent = 'Show More';
          expanded = false;
        } else {
          showAllProducts();
          this.textContent = 'Show Less';
          expanded = true;
        }
      });
    }

    initializeProductDisplay();

    window.addEventListener('resize', function() {
      if (showMoreBtn && showMoreBtn.textContent === 'Show More') {
      showInitialProducts();
      }
    });


    function handleProducts(){
      let single_products = document.querySelectorAll('.shop-product-info');

      if (single_products.length === 0) return;

      single_products.forEach((items) => {
        items.addEventListener('click', (event) => {
          event.preventDefault();

          const productName = items.querySelector('.product-name')?.textContent || "";
          const productImage = items.querySelector('.shop-product-image')?.src || "";
          const productCategory = items.querySelector('.product-category')?.textContent || "";
          const productPrice = items.querySelector('.product-price')?.textContent || "";


          localStorage.setItem('itemName', productName);
          localStorage.setItem('itemImage', productImage);
          localStorage.setItem('itemCategory', productCategory);
          localStorage.setItem('itemPrice', productPrice);

        })
      });
    }
    
    handleProducts();
});