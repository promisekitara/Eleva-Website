<!-- Reusable booking form to be included in both portals -->
<div class="booking-form-container">
    <form action="<?php echo BASE_URL; ?>api/booking.php" method="POST" class="booking-form">
        <h3>Book Your Service</h3>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="phone">Phone:</label>
        <input type="tel" id="phone" name="phone">

        <label for="service">Service of Interest:</label>
        <select id="service" name="service">
            <option value="film-production">Film Production</option>
            <option value="music-production">Music Production</option>
            <option value="other">Other</option>
        </select>
        
        <label for="date">Preferred Date:</label>
        <input type="date" id="date" name="date" required>
        
        <label for="message">Message:</label>
        <textarea id="message" name="message" rows="4"></textarea>
        
        <button type="submit" class="submit-button">Submit Booking</button>
    </form>
</div>