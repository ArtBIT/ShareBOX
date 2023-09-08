<?php
    $this->assets->add_css_file('tablesaw/dist/tablesaw.css', DOCUMENT_HEAD);
    $this->assets->add_js_file('tablesaw/dist/tablesaw.js', DOCUMENT_BODY_END);
?>
<div class="pp">
<div class="table-responsive">
    <table class="table table-striped table-bordered table-hover" data-tablesaw-mode="columntoggle" data-tablesaw-minimap>
        <thead>
            <tr>
                <?php foreach ($headers as $column => $label) {
    ?>
                    <th><?php echo $label; ?></th>
                <?php 
} ?>
            </tr>
        </thead>
        <tbody>
        <?php if (isset($rows) && count($rows)) {
    ?>
            <?php foreach ($rows as $row) {
        ?>
            <tr>

                <?php foreach ($headers as $column => $label) {
            ?>
                    <td><?php echo $row[$column]; ?></td>
                <?php 
        } ?>
            </tr>
            <?php 
    } ?>
        <?php 
} ?>
        </tbody>
    </table>
</div>
<?php echo $pagination; ?>
</div>
