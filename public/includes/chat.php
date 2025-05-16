<!-- Nút nhắn tin Messenger -->
<a href="https://m.me/61554853380334" target="_blank" class="messenger-btn" title="Chat với chúng tôi qua Messenger">
  <i class="bi bi-messenger"></i>
  <span class="messenger-text">Chat với chúng tôi</span>
</a>

<!-- CSS tùy chỉnh -->
<style>
.messenger-btn {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #ffc107; /* Màu vàng khớp với giao diện */
  color: #000;
  padding: 12px 20px;
  border-radius: 50px;
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 1rem;
  font-weight: 600;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
  z-index: 1000;
  text-decoration: none;
  transition: all 0.3s ease;
}

.messenger-btn:hover {
  background-color: #ffca2c; /* Sáng hơn khi hover */
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.4);
}

.messenger-btn .bi-messenger {
  font-size: 1.5rem;
}

.messenger-text {
  display: inline;
}

@media (max-width: 768px) {
  .messenger-btn {
    padding: 10px 15px;
    border-radius: 40px;
  }
  .messenger-text {
    display: none; /* Ẩn text trên mobile, chỉ giữ icon */
  }
  .messenger-btn .bi-messenger {
    font-size: 1.3rem;
  }
}
</style>