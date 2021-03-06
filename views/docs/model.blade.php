<div class="bg-gray-100 card p-3 mt-3 rounded shadow">
    <h2 class="text-2xl">Model</h2>
    <ul>
        <li class="list-decimal ml-4">
            <article class="method">
                <h3 class="border-b border-t font-black mt-2 py-1">Creating a model</h3>
                <p>On app/Models directory, you can find a default model <b>User.php</b>,
                    make a copy of the file and rename it according to your own. And then open the file
                    and change the name of the class</p>
            </article>
        </li>
        <li class="list-decimal ml-4">
            <article class="method">
                <h3 class="border-b border-t font-black mt-2 py-1">Define a relationship</h3>
                <p>Below is the basic way of defining a relationship on model.</p>
                <pre>
                    protected array $relations = [
                        'user_id' => 'App\\Models\\User'
                    ];
                </pre>
            </article>
        </li>
        <li class="list-decimal ml-4">
            <article class="method">
                <h3 class="border-b border-t font-black mt-2 py-1">To get last insert ID</h3>
                <p>$model->insertId()</p>
            </article>
        </li>
    </ul>
</div>
