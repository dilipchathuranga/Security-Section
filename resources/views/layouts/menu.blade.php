<li class="nav-item">
    <a href="{{ route('home') }}" class="nav-link ">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>Dashboard </p>
    </a>
</li>
<li class="nav-item">
    <p style="color: #C2C7D0; font-size: 15px; padding-top:10px">&nbsp;Master</p>
</li>
<li class="nav-item has-treeview">
    <!-- <a href="#" class="nav-link">
      <i class="nav-icon fas fa-circle"></i>
      <p>
        Master
        <i class="right fas fa-angle-right"></i>
      </p>
    </a> -->
    <!-- <ul class="nav nav-treeview"> -->
        <li class="nav-item">
          <a href="{{ url('supplier') }}" class="nav-link">
          &nbsp;<i class="fas fa-building"></i>&nbsp;
            <p> Supplier</p>
          </a>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-users"></i>&nbsp;
              <p>Employee</p>
              <i class="right fas fa-angle-right"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('designation') }}" class="nav-link">
                  &nbsp;<i class="fas fa-portrait"></i>
                    <p>&nbsp;Designation</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('employee') }}" class="nav-link">
                  <i class="fas fa-user-plus"></i>
                    <p>Employee</p>
                  </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-project-diagram"></i>&nbsp;
              <p>Project</p>
              <i class="right fas fa-angle-right"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('contract') }}" class="nav-link">
                  &nbsp;<i class="fas fa-file-contract"></i>
                    <p>&nbsp;Contract</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('project') }}" class="nav-link">
                  <i class="fas fa-project-diagram"></i>
                    <p>Project</p>
                  </a>
                </li>
            </ul>
        </li>
        <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="fas fa-file-alt"></i></i>&nbsp;
              <p>Agreement</p>
              <i class="right fas fa-angle-right"></i>
            </a>
            <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{ route('agreement') }}" class="nav-link">
                    <i class="fas fa-file-contract"></i>
                    <p>&nbsp;Agreement</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{ route('rate') }}" class="nav-link">
                  <i class="fas fa-percentage"></i>
                    <p>Designation Rates</p>
                  </a>
                </li>
            </ul>
        </li>
    <!-- </ul> -->
</li>
<li class="nav-item">
    <p style="color: #C2C7D0; font-size: 15px; padding-top:10px">&nbsp;<i class="fas fa-shield-alt"></i>&nbsp; Security Section</p>
</li>
<li class="nav-item">
  <a href="{{ route('attendance') }}" class="nav-link">
    &nbsp;<i class="fas fa-user-edit"></i>&nbsp;
    <p>Officer Attendance</p>
  </a>
</li>



<li class="nav-item">
    <p style="color: #C2C7D0; font-size: 15px; padding-top:10px">&nbsp;<i class="far fa-chart-bar"></i>&nbsp; Reports</p>
</li>

<li class="nav-item">
  <a href="{{ route('security_report') }}" class="nav-link">
    &nbsp;<i class="fas fa-shield-alt"></i>&nbsp;
    <p>Security Section</p>
  </a>
</li>