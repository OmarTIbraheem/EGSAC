<?php 

    /*
                                                    -To Use Observer Pattern-
        In Main App Do The Follwing:

            1. Create Object From Subject Class
            [eg: $obj_User = new User] Where "User" Is The -Subject- Implementing Interface "ISubject
            2. Create Object From Observer & Pass The Object For The Subject
            [eg: $obj_display = new Observer($obj_User)
            3. Call The Notify Function In The Subject & Pass The Object For The Observer
            [eg: $obj_User->Notify($obj_display)]
    */
    
    interface IObserver
    {
        public function Update();
    }

    interface ISubject
    {
        public function Attach(IObserver $Observer);
        public function Detach(IObserver $Observer);
        public function Notify();
    }

    #############################################################################
    
    /*
    // EXAMPLE ONLY
        class Observer1 implements IObserver     // OBSERVER DISPLAY
        {
            public function __construct(ISubject $Subject)
            {
                $Subject->Attach($this);
            }

            public function Update()
            {
                // Here Goes The Code Which The Observer Displays...
                echo "Observer1 Is Running <br/><hr/><br/>"; 
            }
        }

        class Observer2 implements IObserver     // OBSERVER DISPLAY
        {
            public function __construct(ISubject $Subject)
            {
                $Subject->Attach($this);
            }

            public function Update()
            {
                // Here Goes The Code Which The Observer Displays...
                echo "Observer2 Is Running <br/><hr/><br/>"; 
            }
        }

        class Observer3 implements IObserver     // OBSERVER DISPLAY
        {
            public function __construct(ISubject $Subject)
            {
                $Subject->Attach($this);
            }

            public function Update()
            {
                // Here Goes The Code Which The Observer Displays...
                echo "Observer3 Is Running <br/><hr/><br/>"; 
            }
        }

        class Subject implements ISubject       // SUBJECT USING OBSERVER
        {
            public $ListObserver = [];

            public function Attach(IObserver $Observer)
            {
                $this->ObserverList[] = $Observer;
            }

            public function Detach(IObserver $Observer)
            {
                
            }

            public function Notify()
            {
                foreach ($this->ObserverList as $Observer)
                {
                    $Observer->Update();
                }
            }
        }

        $Subject = new Subject();

        $Display1 = new Observer1($Subject);
        $Display2 = new Observer2($Subject);
        $Display3 = new Observer3($Subject);

        $Subject->Notify();
    ////////////////////////////////////////////////////////////////////////////////
    */

?>