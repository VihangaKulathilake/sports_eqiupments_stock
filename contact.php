<?php
    include 'userHeader.php';
    require_once 'includes/contact.inc.php';
?>

<link rel="stylesheet" href="css/contact.css">

<div class="contact-container">
    <h1>Contact Us</h1>
    <p>We'd love to hear from you! <br>
        If you have any questions, comments, or feedback, please use the form below.</p>

    <!-- Map Section -->
    <div class="location-section">
        <h2>Our Location</h2>
        <div class="google-map-container">
           
            <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d31731.281254809226!2d80.58774779999999!3d6.2095096000000005!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1sMahawaththa%2C%20Andaluwa%2C%20Gomila%2C%20Mawarala!5e0!3m2!1sen!2slk!4v1757696427290!5m2!1sen!2slk" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
        <h2>Customer Reviews</h2>
        <div class="reviews-grid">
            <?php foreach ($reviews as $review) : ?>
                <div class="review-card">
                    <div class="review-header">
                        <span class="review-author"><?php echo htmlspecialchars($review['author']); ?></span>
                        <div class="rating">
                            <?php echo getStarRating($review['rating']); ?>
                        </div>
                    </div>
                    <p class="review-comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Feedback Form Section -->
    <div class="feedback-form-section">
        <h2>Send Us a Message</h2>
        <form id="contact-form">
            <input type="text" id="name" name="name" placeholder="Your Name" required>
            <input type="email" id="email" name="email" placeholder="Your Email" required>
            <textarea id="message" name="message" rows="5" placeholder="Your Message" required></textarea>
            <button type="submit" class="send-message-btn">Send Message</button>
        </form>
    </div>
</div>

<script>
    document.getElementById('contact-form').addEventListener('submit', function(event) {
        event.preventDefault(); 
        const name = document.getElementById('name').value;
        const email = document.getElementById('email').value;
        const message = document.getElementById('message').value;
        const subject = encodeURIComponent(`Contact Form Submission from ${name}`);
        const body = encodeURIComponent(`Name: ${name}\nEmail: ${email}\n\nMessage:\n${message}`);
        
       
        window.location.href = `mailto:sportivosports@gmail.com?subject=${subject}&body=${body}`;
    });
</script>

<?php
    include 'footer.php';
?>