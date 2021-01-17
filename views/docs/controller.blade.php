<div class="bg-gray-100 card p-3 mt-3 rounded shadow">
    <h2 class="text-2xl">Controller</h2>
    <ul>
        <li class="list-decimal ml-4">
            <article class="method">
                <h3 class="border-b border-t font-black mt-2 py-1">Creating a controller</h3>
                <p>On app/Controllers directory, you can find a default controller <b>HomeController.php</b>,
                    make a copy of the file and rename it according to your own. And then open the file
                    and change the name of the class</p>
            </article>
        </li>
        <li class="list-decimal ml-4">
            <article class="method">
                <h3 class="border-b border-t font-black mt-2 py-1">Getting the upload file</h3>
                <p>So to get the file on controller, it should be <br><code>$request->file_name</code></p>
            </article>
        </li>
        <li class="list-decimal ml-4">
            <article class="method">
                <h3 class="border-b border-t font-black mt-2 py-1">Making a XHR request</h3>
                <p>If request is via XHR, to response or return resulting data use <strong>json()</strong> method</p>
                <strong>Ex.</strong>
                <pre>
                    return $this->app()->json([
                        'message' => 'Successfully deleted draft!'
                    ]);
                </pre>
            </article>
        </li>
    </ul>
</div>
