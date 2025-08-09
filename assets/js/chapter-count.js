jQuery(document).ready(function($) {
    // Lấy tất cả các element có class chapter-count
    const chapterCountElements = document.querySelectorAll('.chapter-count');
    
    // Tạo một queue để xử lý từng request một
    const queue = Array.from(chapterCountElements);
    
    // Hàm xử lý một request với retry mechanism
    function processNextRequest(retryCount = 0) {
        if (queue.length === 0) return;
        
        const element = queue.shift();
        const truyenId = element.dataset.truyenId;
        
        // Kiểm tra xem element có tồn tại không
        if (!element || !truyenId) {
            setTimeout(processNextRequest, 100);
            return;
        }
        
        $.ajax({
            url: chapter_count_ajax.ajax_url,
            type: 'POST',
            data: {
                action: 'get_chapter_count',
                truyen_id: truyenId,
                nonce: chapter_count_ajax.nonce
            },
            success: function(response) {
                if (response.success) {
                    element.textContent = `${response.data.count}`;
                } else {
                    console.error('Error loading chapter count:', response.data);
                    if (retryCount < 3) {
                        // Thêm lại vào queue để thử lại
                        queue.unshift(element);
                        setTimeout(() => processNextRequest(retryCount + 1), 1000);
                    } else {
                        element.textContent = 'Lỗi tải số chương';
                    }
                }
                
                // Xử lý request tiếp theo sau 100ms
                setTimeout(processNextRequest, 100);
            },
            error: function(xhr, status, error) {
                console.error('AJAX error:', error);
                if (retryCount < 3) {
                    // Thêm lại vào queue để thử lại
                    queue.unshift(element);
                    setTimeout(() => processNextRequest(retryCount + 1), 1000);
                } else {
                    element.textContent = 'Lỗi tải số chương';
                }
                setTimeout(processNextRequest, 100);
            }
        });
    }
    
    // Bắt đầu xử lý queue
    if (queue.length > 0) {
        processNextRequest();
    }
}); 