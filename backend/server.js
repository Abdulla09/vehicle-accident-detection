const http = require('http');
const express = require('express');
const socketIo = require('socket.io');
const path = require('path');
const multer = require('multer');
const cors = require('cors');

const app = express();
const server = http.createServer(app);
const io = socketIo(server, {
  cors: {
    origin: "http://localhost:3001", // Adjust the origin to your React app's URL
    methods: ["GET", "POST"]
  }
});

// Use CORS middleware
app.use(cors({ origin: 'http://localhost:3001' }));

// Serve uploaded images
app.use('/uploads', express.static(path.join(__dirname, 'uploads')));

// Multer configuration
const storage = multer.diskStorage({
  destination: './uploads',
  filename: function (req, file, cb) {
    cb(null, file.fieldname + '-' + Date.now() + path.extname(file.originalname));
  }
});
const upload = multer({ storage: storage });

// Endpoint to handle image uploads
app.post('/upload', upload.single('file'), (req, res) => {
  const imageUrl = `/uploads/${req.file.filename}`;

  // Emit a 'newImage' event to all connected clients
  io.emit('newImage', imageUrl);

  res.json({ imageUrl });
});

io.on('connection', (socket) => {
  console.log('Client connected');

  socket.on('disconnect', () => {
    console.log('Client disconnected');
  });
});

// Other routes or configurations can be added here

server.listen(3000, () => {
  console.log('Server is running on port 3000');
});
