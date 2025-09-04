    </main>

    <footer class="footer">
      <div class="footer_box">
          <h3>About Sportivo</h3>
          <p>Sportivo is your one-stop destination for premium sports equipment and accessories. We bring quality, performance, and style together to help athletes and enthusiasts achieve their best every day.</p>
          <div class="social">
              <a href="#"><i class='bx bxl-facebook-circle'></i></a>
              <a href="#"><i class='bx bxl-twitter'></i></a>
              <a href="#"><i class='bx bxl-instagram-alt'></i></a>
              <a href="#"><i class='bx bxl-tiktok'></i></a>
          </div>
      </div>

      <div class="footer_box">
          <h3>Products</h3>
          <ul>
              <li><a href="#">All Sports Equipment</a></li>
              <li><a href="#">Team & Individual Gear</a></li>
              <li><a href="#">Fitness Accessories</a></li>
              <li><a href="#">Training & Coaching Tools</a></li>
              <li><a href="#">New Arrivals</a></li>
              <li><a href="#">Best Sellers</a></li>
              <li><a href="#">Sale Items</a></li>
          </ul>
      </div>

      <div class="footer_box">
          <h3>Support / Terms</h3>
          <ul>
              <li><a href="#">Terms of Service</a></li>
              <li><a href="#">Privacy Policy</a></li>
              <li><a href="#">Return & Refund Policy</a></li>
              <li><a href="#">Shipping & Delivery Policy</a></li>
              <li><a href="#">Help & Support</a></li>
              <li><a href="#">FAQs</a></li>
              <li><a href="#">Warranty Information</a></li>
          </ul>
      </div>

      <div class="footer_box">
          <h3>Contact</h3>
          <div class="Contact">
              <span><i class='bx bx-map'></i>Mahawaththa, Andaluwa, Gomila, Mawarala</span>
              <span><i class='bx bxs-phone-call'></i>0775227202</span>
              <span><i class='bx bxs-envelope'></i>sportivosports@gmail.com</span>
          </div>
      </div>
    </footer>
  </div>

    <div class="footer-bottom">
        <p>© 2025 Sportivo. All Rights Reserved.</p>
    </div>

  <script>
    const toggleBtn = document.querySelector(".menuToggle");
    const sidebar = document.querySelector(".sidebar");
    const overlay = document.querySelector(".overlay");

    toggleBtn.addEventListener("click", () => {
      sidebar.classList.toggle("active");
      overlay.classList.toggle("active");
    });

    overlay.addEventListener("click", () => {
      sidebar.classList.remove("active");
      overlay.classList.remove("active");
    });
  </script>
</body>
</html>
