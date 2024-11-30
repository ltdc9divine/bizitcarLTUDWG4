// Menu toggle
let menu = document.querySelector('#menu-btn');
let navbar = document.querySelector('.navbar');

menu.onclick = () => {
  menu.classList.toggle('fa-times');
  navbar.classList.toggle('active');
};

document.querySelector('#login-btn').onclick = () => {
  document.querySelector('.login-form-container').classList.toggle('active');
};

document.querySelector('#close-login-form').onclick = () => {
  document.querySelector('.login-form-container').classList.remove('active');
};

window.onscroll = () => {
  menu.classList.remove('fa-times');
  navbar.classList.remove('active');

  if (window.scrollY > 0) {
    document.querySelector('.header').classList.add('active');
  } else {
    document.querySelector('.header').classList.remove('active');
  }
};

document.querySelector('.home').onmousemove = (e) => {
  document.querySelectorAll('.home-parallax').forEach(elm => {
    let speed = elm.getAttribute('data-speed');
    let x = (window.innerWidth - e.pageX * speed) / 90;
    let y = (window.innerHeight - e.pageY * speed) / 90;
    elm.style.transform = `translateX(${y}px) translateY(${x}px)`;
  });
};

document.querySelector('.home').onmouseleave = () => {
  document.querySelectorAll('.home-parallax').forEach(elm => {
    elm.style.transform = `translateX(0px) translateY(0px)`;
  });
};
document.getElementById('subscribe-form').addEventListener('submit', function(event) {
  event.preventDefault(); // Ngăn chặn việc gửi form mặc định
  const email = event.target.querySelector('input[type="email"]').value; // Lấy giá trị email từ input

  // Gửi email đến PHP script
  fetch('subscribe.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: `email=${encodeURIComponent(email)}` // Mã hóa email cho yêu cầu
  })
  .then(response => response.json())
  .then(data => {
      console.log(data); // Ghi log phản hồi để kiểm tra
      if (data.success) {
          alert(data.message); // Hiển thị thông báo thành công
          event.target.reset(); // Đặt lại form
      } else {
          alert('Error: ' + data.message); // Hiển thị thông báo lỗi
      }
  })
  .catch(error => {
      console.error('Error:', error);
      alert('Failed to connect to the server.');
  });
});

// Swiper initialization
var swiperVehicles = new Swiper(".vehicles-slider", {
  grabCursor: true,
  centeredSlides: true,
  spaceBetween: 20,
  loop: true,
  autoplay: false,
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 3,
    },
  },
});

var swiperReviews = new Swiper(".review-slider", {
  grabCursor: true,
  centeredSlides: true,
  spaceBetween: 20,
  loop: true,
  autoplay: {
    delay: 9500,
    disableOnInteraction: false,
  },
  pagination: {
    el: ".swiper-pagination",
    clickable: true,
  },
  breakpoints: {
    0: {
      slidesPerView: 1,
    },
    768: {
      slidesPerView: 2,
    },
    1024: {
      slidesPerView: 3,
    },
  },
});

// Fetch vehicles and display
fetch('fetch_vehicles.php')
  .then(response => response.json())
  .then(data => {
    const container = document.getElementById('vehicles-container');
    data.forEach(vehicle => {
      const vehicleHTML = `
        <div class="swiper-slide box">
          <img src="${vehicle.image.trim()}" alt="${vehicle.name}">
          <div class="content">
            <h3>${vehicle.name}</h3>
            <div class="price"><span>Price:</span> $${vehicle.price}</div>
            <p>${vehicle.description}</p>
            <a href="#" class="btn schedule-viewing" data-vehicle="${vehicle.name}">Schedule a car viewing</a>
          </div>
        </div>
      `;
      container.innerHTML += vehicleHTML;
    });

    // Reinitialize Swiper after content load
    new Swiper('.vehicles-slider', {
      loop: true,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      autoplay: false,
      slidesPerView: 1,
      spaceBetween: 10,
      breakpoints: {
        640: {
          slidesPerView: 2,
          spaceBetween: 20,
        },
        768: {
          slidesPerView: 3,
          spaceBetween: 40,
        },
      },
    });

    // Add event listener to "Schedule a car viewing" buttons
    document.querySelectorAll('.schedule-viewing').forEach(button => {
      button.addEventListener('click', (e) => {
        e.preventDefault();
        const carName = button.getAttribute('data-vehicle');
        document.getElementById('car').value = carName;
        document.getElementById('schedule-modal').style.display = 'flex';
      });
    });
  })
  .catch(error => console.error('Error fetching data:', error));

// Modal logic
document.getElementById('close-modal').addEventListener('click', () => {
  document.getElementById('schedule-modal').style.display = 'none';
});

window.addEventListener('click', (e) => {
  if (e.target === document.getElementById('schedule-modal')) {
    document.getElementById('schedule-modal').style.display = 'none';
  }
});


// Handle schedule form submission
document.getElementById('schedule-form').addEventListener('submit', (e) => {
  e.preventDefault();

  const name = document.getElementById('name').value;
  const phone = document.getElementById('phone').value;
  const email = document.getElementById('email').value;
  const car = document.getElementById('car').value;
  const date = document.getElementById('date').value;
  const time = document.getElementById('time').value;

  // Gửi dữ liệu qua API
  fetch('save_appointment.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
    },
    body: JSON.stringify({
      name: name,
      phone: phone,
      email: email,
      car: car,
      date: date,
      time: time,
    }),
  })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Appointment booked successfully!');
        document.getElementById('schedule-modal').style.display = 'none';
        e.target.reset();
      } else {
        alert('Error booking appointment: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Failed to connect to the server.');
    });
});
// Handle contact form submission
document.getElementById('contact-form').addEventListener('submit', (event) => {
  event.preventDefault(); // Ngăn chặn việc gửi form mặc định

  // Lấy giá trị từ các trường input
  const name = document.querySelector('input[name="name"]').value; // Lấy giá trị tên
  const email = document.querySelector('input[name="email"]').value; // Lấy giá trị email
  const subject = document.querySelector('input[name="subject"]').value; // Lấy giá trị chủ đề
  const message = document.querySelector('textarea[name="message"]').value; // Lấy giá trị tin nhắn

  // Kiểm tra xem tất cả các trường có được điền không
  if (!name || !email || !subject || !message) {
      alert('Please fill in all fields.');
      return;
  }

  // Gửi dữ liệu đến PHP script
  fetch('contact.php', {
      method: 'POST',
      headers: {
          'Content-Type': 'application/json', // Thay đổi loại nội dung
      },
      body: JSON.stringify({
          name: name,
          email: email,
          subject: subject,
          message: message
      }) // Chuyển đổi dữ liệu thành chuỗi JSON
  })
  .then(response => {
      if (!response.ok) {
          throw new Error('Network response was not ok ' + response.statusText);
      }
      return response.json();
  })
  .then(data => {
      console.log(data); // Ghi log phản hồi để kiểm tra
      if (data.success) {
          alert(data.message); // Hiển thị thông báo thành công
          event.target.reset(); // Đặt lại form
      } else {
          alert('Error: ' + data.message); // Hiển thị thông báo lỗi
      }
  })
  .catch(error => {
      console.error('Error:', error);
      alert('Failed to connect to the server.');
  });
});