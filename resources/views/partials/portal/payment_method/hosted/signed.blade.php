<card-form
    :form-data="formData"
    @input-card-number="updateCardNumber"
    @input-card-name="updateCardName"
    @input-card-month="updateCardMonth"
    @input-card-year="updateCardYear"
    @input-card-cvv="updateCardCvv"
/>
