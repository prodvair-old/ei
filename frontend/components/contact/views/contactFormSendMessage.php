<form id="contact-form" method="post" action="contact-2.php">

    <div class="contact-successful-messages"></div>

    <div class="contact-inner">

        <div class="form-group">
            <label for="form_name">Имя</label>
            <input id="form_name" type="text" name="name" class="form-control" required="required" data-error="Your name is required.">
            <div class="help-block with-errors text-danger"></div>
        </div>
        
        <div class="form-group">
            <label for="form_email">Email</label>
            <input id="form_email" type="email" name="email" class="form-control" required="required" data-error="Valid email is required.">
            <div class="help-block with-errors text-danger"></div>
        </div>
        
        <div class="form-group">
            <label for="form_subject">Тема</label>
            <input id="form_subject" type="text" name="email" class="form-control">
        </div>
        
        <div class="form-group">
            <label for="form_message">Сообщение</label>
            <textarea id="form_message" name="message" class="form-control" rows="7s" required="required" data-error="Please,leave us a message."></textarea>
            <div class="help-block with-errors text-danger"></div>
        </div>
        
        <input type="submit" class="btn btn-primary btn-send btn-wide mt-15" value="Отправить сообщение">
        
    </div>

</form>