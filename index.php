<?php

include 'app/bowling.php';

$bowling_game = new BowlingGame();
$bowling_score = 0;
$bowling_score_formatted = array();

if( isset( $_POST['score'] ) ) {
    $bowling_score = $bowling_game->calculateScore( $_POST['score'] );
    $bowling_score_formatted = $bowling_game->getFormattedScore( $_POST['score'] );
}

?>

<!doctype html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>Bowling Score Calculator</title>
    </head>
    <body>
    <div class="flex flex-col items-center justify-center min-h-screen">
        <div class="container">
            <div class="w-full max-w-[90vw]">
                <div class="py-8">
                    <h1 class="text-3xl font-bold text-black">Bowling Score Calculator</h1>
                </div>

                <?php if( !isset( $_POST['score'] ) ) : ?>
                    <form class="py-6" method="post" action="/">
                        <div class="mb-4">
                            <label for="score" class="block font-bold mb-2">Enter your score:</label>
                            <input type="text" id="score" name="score" required="required" class="w-full px-3 py-2 border-gray-700 border-[1px] bg-gray-50 focus:outline-none">
                        </div>
                        <button type="submit" class="bg-gray-200 text-black font-bold py-2 px-4 focus:outline-none focus:shadow-outline hover:bg-ponderosa-orange hover:text-ponderosa-black transition duration-500 ease-in-out">Calculate Score</button>
                    </form>
                <?php else: ?>

                    <div class="py-6">
                        <p class="block mb-2"><span class="font-bold">Total score: </span> <?php echo $bowling_game->getTotalScore(); ?></p>
                        <p class="block mb-2"><span class="font-bold">Frames played: </span> <?php echo $bowling_game->getTotalFrames(); ?></p>
                    </div>

                    <?php if( !empty( $bowling_score_formatted ) )  : ?>
                        <table class="table-fixed">
                            <thead>
                                <tr>
                                <?php for ($x = 0; $x <= 10; $x++) : ?>
                                    <th class="bg-gray-200 py-6 px-2">Frame <?php echo $x; ?></th>
                                <?php endfor; ?>
                                    <th class="bg-gray-200 py-6 px-2">Total Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                <?php for ($x = 0; $x <= 10; $x++) : ?>
                                    <td class="border-gray-200 border-[1px] text-center p-0">
                                        <?php if( isset( $bowling_score_formatted[$x] ) ) : ?>
                                            <table class="table-fixed w-full">
                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            &nbsp;
                                                        </td>
                                                        <td class="border-gray-200 border-[1px] text-center p-2">
                                                            <?php echo $bowling_score_formatted[$x]['bowl_1']; ?>
                                                        </td>
                                                        <td class="border-gray-200 border-[1px] text-center p-2">
                                                            <?php echo $bowling_score_formatted[$x]['bowl_2']; ?>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-center p-2">
                                                            <?php echo $bowling_score_formatted[$x]['score']; ?>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <?php else: ?>
                                            <table class="table-auto">
                                                <tbody>
                                                    <tr>
                                                        <td colspan="3" class="text-center p-2">
                                                            -
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        <?php endif; ?>
                                    </td>
                                <?php endfor; ?>
                                    <td class="border-gray-200 border-[1px] text-center p-2">
                                        <?php echo $bowling_game->getTotalScore(); ?>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif; ?>

                    <form class="mt-20 py-6" method="post" action="/">
                        <div class="mb-2">
                            <h2 class="text-lg font-bold text-black">Bowl again?</h2>
                        </div>
                        <div class="mb-4">
                            <label for="score" class="block font-bold mb-2">Enter your score:</label>
                            <input type="text" id="score" name="score" required="required" class="w-full px-3 py-2 border-gray-700 border-[1px] bg-gray-50 focus:outline-none">
                        </div>
                        <button type="submit" class="bg-gray-200 text-black font-bold py-2 px-4 focus:outline-none focus:shadow-outline hover:bg-ponderosa-orange hover:text-ponderosa-black transition duration-500 ease-in-out">Calculate Score</button>
                    </form>

                <?php endif; ?>
            </div>
        </div>
    </div>
</html>