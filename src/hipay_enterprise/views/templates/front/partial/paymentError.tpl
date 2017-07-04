{if $status_error=='200'}
    <div id="error-js" style="display:none" class="alert alert-danger">
        <p>There is 1 error</p>
        <ol>
            <li class="error"></li>
        </ol>
    </div>
{else if $status_error=='400'}
    <div class="alert alert-danger">
        <p>There is 1 error</p>
        <ol>
            <li>{l s='The request was rejected due to a validation error. Please verify the card details you entered.' mod='hipay_tpp'}</li>
        </ol>
    </div>
{else if $status_error=='503'}
    <div class="alert alert-danger">
        <p>There is 1 error</p>
        <ol>
            <li>{l s='HiPay TPP is temporarily unable to process the request. Try again later.' mod='hipay_tpp'}</li>
        </ol>
    </div>
{else if $status_error=='403'}
    <div class="alert alert-danger">
        <p>There is 1 error</p>
        <ol>
            <li>{l s='A forbidden action has been identified, process has been cancelled.' mod='hipay_tpp'}</li>
        </ol>
    </div>
{else if $status_error=='999'}
    <div class="alert alert-danger">
        <p>There is 1 error</p>
        <ol>
            <li>{l s='Please select one of the memorized card before continuing.' mod='hipay_tpp'}</li>
        </ol>
    </div>
{else if $status_error=='404'}
    <div class="alert alert-danger">
        <p>There is 1 error</p>
        <ol>
            <li>{l s='This credit card type or the order currency is not supported. Please choose a other payment method.' mod='hipay_tpp'}</li>
        </ol>
    </div>
{else}
    <div class="alert alert-danger">
        <p>There is 1 error</p>
        <ol>
            <li>{l s='An error occured, process has been cancelled.' mod='hipay_tpp'}</li>
        </ol>
    </div>
{/if}