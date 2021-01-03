<?php
session_start();
require '../config/config.php';

if (empty($_SESSION['user_id'] && $_SESSION['logged_in'])) {
  header('Location: login.php');
}
if ($_POST) {
  $id = $_POST['id'];
  $name = $_POST['name'];
  $email = $_POST['email'];
  if(empty($_POST['role'])){
    $role = 0;
  }else{
    $role = 1;
  }

  $sql = $pdo->prepare("SELECT * FROM users WHERE email=:email AND id!=:id");
  $sql->execute(array(':email'=>$email,':id'=>$id));
  $user = $sql->fetch(PDO::FETCH_ASSOC);

  if($user){
    echo "<script>alert('Email duplicated!')</script>";
  }else {
    $stmt = $pdo->prepare("UPDATE users SET name='$name',email='$email',role='$role' WHERE id='$id'");
    $result = $stmt->execute();
    if($result){
      echo "<script>
      alert('Successfully Updated!');
      window.location.href='user_list.php';
      </script>";
    }
  }
}

$stmt = $pdo->prepare("SELECT * FROM users WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

include('header.php');
 ?>



     <!-- Main content -->
     <div class="content">
       <div class="container-fluid">
         <div class="row">
           <div class="col-md-12">
             <div class="card">
             <div class="card-body">
               <form class="" action="" method="post" enctype="multipart/form-data">
                 <div class="form-group">
                   <input type="hidden" name="id" value="<?php echo $result[0]['id'] ?>">
                   <label for="">Name</label>
                   <input type="text" class="form-control" name="name" value="<?php echo $result[0]['name'] ?>" required>
                 </div>
                 <div class="form-group">
                   <label for="">Email</label><br>
                   <input type="email" class="form-control" name="email" value="<?php echo $result[0]['email'] ?>" required>
                 </div>
                 <div class="form-group">
                   <label for="vehicle3">Admin</label><br>
                   <input type="checkbox" name="role" value="1">
                 </div>
                 <div class="form-group">
                   <input type="submit" class="btn btn-success" name="" value="SUBMIT">
                   <a href="user_list.php" class="btn btn-default">Back</a>
                 </div>
               </form>
             </div>
             </div>
             <!-- /.card -->


           </div>
           <!-- /.col -->
         </div>
         <!-- /.row -->
       </div><!-- /.container-fluid -->
     </div>
     <!-- /.content -->
<?php include('footer.html'); ?>
