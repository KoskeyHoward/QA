import Database from "better-sqlite3"
import path from "path"

const dbPath = path.join(process.cwd(), "feedback.db")
const db = new Database(dbPath)

// Initialize database
db.exec(`
  CREATE TABLE IF NOT EXISTS feedback (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    email TEXT NOT NULL,
    message TEXT NOT NULL,
    rating INTEGER CHECK(rating >= 1 AND rating <= 5),
    approved INTEGER DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
  );

  CREATE TABLE IF NOT EXISTS admin (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL
  );

  INSERT OR IGNORE INTO admin (username, password) VALUES ('admin', 'admin123');
`)

export interface Feedback {
  id: number
  name: string
  email: string
  message: string
  rating?: number
  approved: boolean
  created_at: string
}

export interface Admin {
  id: number
  username: string
  password: string
}

export const feedbackDb = {
  // Get all approved feedback
  getApprovedFeedback: () => {
    const stmt = db.prepare("SELECT * FROM feedback WHERE approved = 1 ORDER BY created_at DESC")
    const results = stmt.all() as any[]
    return results.map((row) => ({
      ...row,
      approved: Boolean(row.approved),
    })) as Feedback[]
  },

  // Get all feedback (for admin)
  getAllFeedback: () => {
    const stmt = db.prepare("SELECT * FROM feedback ORDER BY created_at DESC")
    const results = stmt.all() as any[]
    return results.map((row) => ({
      ...row,
      approved: Boolean(row.approved),
    })) as Feedback[]
  },

  // Create new feedback
  createFeedback: (feedback: Omit<Feedback, "id" | "approved" | "created_at">) => {
    const stmt = db.prepare("INSERT INTO feedback (name, email, message, rating, approved) VALUES (?, ?, ?, ?, 0)")
    const result = stmt.run(feedback.name, feedback.email, feedback.message, feedback.rating || null)
    return result.lastInsertRowid
  },

  // Update feedback (approve/disapprove)
  updateFeedback: (id: number, approved: boolean) => {
    const stmt = db.prepare("UPDATE feedback SET approved = ? WHERE id = ?")
    return stmt.run(approved ? 1 : 0, id)
  },

  // Delete feedback
  deleteFeedback: (id: number) => {
    const stmt = db.prepare("DELETE FROM feedback WHERE id = ?")
    return stmt.run(id)
  },

  // Get feedback by ID
  getFeedbackById: (id: number) => {
    const stmt = db.prepare("SELECT * FROM feedback WHERE id = ?")
    const result = stmt.get(id) as any
    if (result) {
      return {
        ...result,
        approved: Boolean(result.approved),
      } as Feedback
    }
    return undefined
  },

  // Admin authentication
  getAdmin: (username: string, password: string) => {
    const stmt = db.prepare("SELECT * FROM admin WHERE username = ? AND password = ?")
    return stmt.get(username, password) as Admin | undefined
  },
}

export default db
