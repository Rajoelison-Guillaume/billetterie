<h5 class="mt-4">Mode de paiement :</h5>
<div class="mb-3">
    <select name="payment_method" id="payment_method" class="form-select" required>
        <option value="cash">Cash</option>
        <option value="mobile_money">Mobile Money</option>
    </select>
</div>

<div id="mobile-money-fields" style="display:none;">
    <div class="mb-3">
        <label for="provider" class="form-label">Opérateur</label>
        <select name="provider" class="form-select">
            <option value="MVola">MVola</option>
            <option value="OrangeMoney">Orange Money</option>
            <option value="AirtelMoney">Airtel Money</option>
        </select>
    </div>
    <div class="mb-3">
        <label for="provider_ref" class="form-label">Code transaction</label>
        <input type="text" name="provider_ref" class="form-control">
    </div>
</div>

<script>
    document.getElementById('payment_method').addEventListener('change', function() {
        document.getElementById('mobile-money-fields').style.display = 
            this.value === 'mobile_money' ? 'block' : 'none';
    });
</script>
