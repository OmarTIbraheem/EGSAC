<?php require_once '../Pages/Index.php'; ?>
<div class="Content">

<?php

    class Archives
    {
        private $Data;
        
        public function __construct($Data)
        {
            $this->Data = $Data;
        }

        public function Archive()
        {
            if (!empty($this->Data))
            {
                $Reports = $this->Data['Reports'];
                $Publications = $this->Data['Publications'];
            }

            echo
            '
                <div class="Archive">
                    <h1>Archives<i class="fas fa-archive" style="position:absolute; top:38px; right:35px; color:crimson;"></i></h1>
                    <div id="Links">
            ';
            
            if (!empty($Reports))
            {
                echo
                '
                    <a class="btn-default" href="Archive.controller.php?Archive=Report"  style="opacity:1;">Show Reports <i class="fas fa-file-contract" style="position:absolute; left:42%; top:133px; font-size:100px;"></i></a>
                    <a class="btn-default" href="Archive.controller.php?Archive=Publication">Show Publications <i class="fas fa-newspaper" style="position:absolute; left:92%; top:133px; font-size:100px;"></i></a>
                ';
            } else if (!empty($Publications)) {
                echo
                '
                    <a class="btn-default" href="Archive.controller.php?Archive=Report">Show Reports <i class="fas fa-file-contract" style="position:absolute; left:42%; top:133px; font-size:100px;"></i></a>
                    <a class="btn-default" href="Archive.controller.php?Archive=Publication" style="opacity:1;">Show Publications <i class="fas fa-newspaper" style="position:absolute; left:92%; top:133px; font-size:100px;"></i></a>
                ';
            } else {
                echo
                '
                    <a class="btn-default" href="Archive.controller.php?Archive=Report">Show Reports <i class="fas fa-file-contract" style="position:absolute; left:42%; top:133px; font-size:100px;"></i></a>
                    <a class="btn-default" href="Archive.controller.php?Archive=Publication">Show Publications <i class="fas fa-newspaper" style="position:absolute; left:92%; top:133px; font-size:100px;"></i></a>
                ';
            }
            
            echo
            '    
                    </div>
                    <hr/><br/><br/>
            ';

            if (!empty($Reports)) {
                #Code...
            } else if (!empty($Publications)) {
                echo
                '
                    
                    <table>
                        <tr>
                            <th style="width:5%;">ID</th>
                            <th style="width:25%;">Journal</th>
                            <th style="width:10%;">Author</th>
                            <th style="width:40%;">Title</th>
                            <th style="width:10%;">Published</th>
                            <th style="width:100px;">Action</th>
                        </tr>
                ';

                foreach ($Publications as $PUB)
                {
                    $Data = urlencode(base64_encode($PUB[5]));

                    echo
                    '
                        <tr>
                            <td>' . $PUB[5] . '</td>
                            <td>' . $PUB[1] . '</td>
                            <td>' . $PUB[2] . '</td>
                            <td>' . $PUB[3] . '</td>
                            <td>' . $PUB[4] . '</td>
                            <td>
                                <a class="btn-info" href="Archive.controller.php?Publication=View&PubID=' . $Data . '">View</a>
                                <a class="btn-danger" href="Archive.controller.php?Publication=Remove&PubID=' . $Data . '">Remove</a>
                            </td>
                        </tr>
                    ';
                }

                echo
                '
                    </table>
                ';
            }

            echo
            '
                </div>
            ';
        }

        public function Publication()
        {
            $Publication = $this->Data;
            $Title = $Publication[0];
            $Abstract = $Publication[1];
            $Introduction = $Publication[2];
            $Body = $Publication[3];
            $Conclusion = $Publication[4];
            $Refrence = $Publication[5];
            $FileDIR = $Publication[6];

            echo
            '
                <div class="Publication">
                    <h1>' . $Title . '</h1>
                    <section>' . $Abstract . '</section>
            ';

            if (!empty($FileDIR)) {
                
            } else {

            }

            if (!empty($Introduction) || !empty($Body) || !empty($Conclusion) || !empty($Refrence))
            {
                echo
                '   
                    <span>
                        <h3>Introduction</h3><hr/>
                    </span>
                    <p>' . $Introduction . '</p>
                    <span>
                        <h3>Body</h3><hr/>
                    </span>
                    <p>' . $Body . '</p>
                    <span>
                        <h3>Conclusion</h3><hr/>
                    </span>
                    <p>' . $Conclusion . '</p>
                    <span>
                        <h3>Refrence</h3><hr/>
                    </span>
                    <p>' . $Refrence . '</p>
                ';
            }

            echo
            '
                </div>
            ';
        }
    }

?>