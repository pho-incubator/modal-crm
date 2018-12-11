<?= view('inc/header.php') ?>
<?= view('inc/navbar.php') ?>

<div class="container">
    <h1>Ticket Conversation</h1>

    <div class="mt-3">
        <div>Ticket ID: <?= $ticket->uuid ?></div>
        <div>Title: <?= $ticket->title ?></div>
        <div>Type: <?= $ticketTypeToText($ticket->type) ?></div>
        <div>By: <?= "{$ticket->byUser->first_name} {$ticket->byUser->last_name}" ?></div>
        <div>Assigned To: <?= $ticket->assigneeUser ? "{$ticket->assigneeUser->first_name} {$ticket->assigneeUser->last_name}" : '<i>Not Assigned</i>' ?></div>
        <div>Status: <?= $ticketStatusToText($ticket->status) ?></div>
    </div>

    <div class="mt-4">
        <form method="post" action="<?= url("service-tickets/{$ticket->uuid}/reply") ?>">
            <?php if (isset($fail_message)): ?>
                <div class="text-danger"><?= $fail_message ?></div>
            <?php endif ?>
            <label for="reply">Reply</label>
            <div class="form-group">
                <textarea name="text" id="reply" class="form-control"></textarea>
                <?php if (isset($errors) && $errors->has('text')): ?>
                    <div class="text-danger"><?= $errors->first('text') ?></div>
                <?php endif ?>
            </div>
            <div class="clearfix mt-2">
                <button type="submit" class="btn btn-primary float-right">Reply</button>
                <button type="button" id="btn-canned-response" class="btn btn-secondary float-right mr-2">Load Canned Response</button>
            </div>
        </form>
    </div>

    <div class="mt-4">
        <?php foreach ($conversations as $conversation): ?>
            <div class="border mb-5">
                <div class="border-bottom p-2">
                    <span class="fas fa-user"></span>
                    <?= "{$conversation->user->first_name} {$conversation->user->last_name}" ?>
                    <span class="float-right">
                        <span class="fas fa-calendar"></span>
                        <?= $conversation->created_at ?>
                    </span>
                </div>
                <div class="p-2"><?= $conversation->text ?></div>
            </div>
        <?php endforeach ?>
    </div>
</div>

<script>

(function ($) {
    'use strict';

    var cannedResponses = <?= json_encode($cannedResponses) ?>;
    var $reply = $('#reply');

    var popoverContent = `
<div class="list-group">
    ${cannedResponses.map(res => `<a href="#" tabindex="0" data-text="${res}" class="btn-insert list-group-item list-group-item-action">${res}</a>`).join('')}
</div>
`;
    var $popoverContent = $(popoverContent);
    $popoverContent.find('.btn-insert').on('click', function (ev) {
        ev.preventDefault();

        $reply.text($(this).data('text'));
    });
    $('#btn-canned-response').popover({
        placement: 'top',
        html: true,
        content: $popoverContent,
        trigger: 'focus',
    });

})(jQuery);

</script>

<?= view('inc/footer.php') ?>
