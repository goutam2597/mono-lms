# Ostad App Reverse Engineering & REA MCP Integration Guide

This document records the complete process of setting up the **REA (Reverse Engineer Anything)** MCP server, analyzing the **Ostad** desktop application, extracting its cohort learning workflows, implementing those high-value features in the **Mono LMS** Laravel web platform, and configuring REA in other IDEs (Cursor, Claude Desktop, VS Code, Windsurf, etc.).

---

## Table of Contents

1. [Overview](#1-overview)
2. [How REA MCP Was Installed & Configured](#2-how-rea-mcp-was-installed--configured)
3. [How to Install & Use REA in Other IDEs](#3-how-to-install--use-rea-in-other-ides)
   - [Cursor](#a-cursor)
   - [Claude Desktop](#b-claude-desktop)
   - [VS Code (Cline / Roo Code)](#c-vs-code-cline--roo-code)
   - [Windsurf](#d-windsurf)
   - [Direct CLI Usage](#e-direct-cli-usage-outside-mcp)
4. [Reverse Engineering the Ostad Desktop App](#4-reverse-engineering-the-ostad-desktop-app)
   - [Locating the Target](#locating-the-target)
   - [Architecture Discovery](#architecture-discovery)
   - [Analysis with REA](#analysis-with-rea)
   - [Key Components & Workflows Extracted](#key-components--workflows-extracted)
5. [What Work Was Implemented in Mono LMS](#5-what-work-was-implemented-in-mono-lms)
   - [A. Assignments & Hands-on Projects Engine](#a-assignments--hands-on-projects-engine)
   - [B. Cohort Leaderboard & Gamification](#b-cohort-leaderboard--gamification)
   - [C. Live Class Session Types & Attendance Tracking](#c-live-class-session-types--attendance-tracking)
   - [D. UI Views & Integration](#d-ui-views--integration)
6. [Testing, Verification & Git Sync](#6-testing-verification--git-sync)

---

## 1. Overview

- **Source Reference**: [morluto/rea](https://github.com/morluto/rea.git) (Reverse Engineer Anything).
- **Target Application**: Ostad Desktop (`C:\Users\GoutamSharma\AppData\Local\Ostad\ostad.exe`).
- **Goal**: Reverse engineer Ostad to identify the features that make it effective as a live cohort-based learning platform, and implement those features natively for the web in the Mono LMS Laravel application.

---

## 2. How REA MCP Was Installed & Configured

### Installation
REA was installed globally via Node.js / npm to ensure fast startup without repeated download latency:

```bash
npm install -g rea-agents
```

*(Alternatively, it can be run on-demand via `npx -y rea-agents@latest`)*

### Verification Check
We verified the integrity of the server adapter:

```bash
rea mcp doctor
```

**Result:**
- Adapter: `production-stdio`
- Version: `6.9.0`
- Available Tools: **255/255 healthy**
- Available Prompts: **6/6 healthy**

### Configuration in Antigravity
The REA MCP server was registered in both global and session MCP configuration files:
- `~/.gemini/config/mcp_config.json`
- `~/.gemini/antigravity/mcp_config.json`

```json
{
  "mcpServers": {
    "figma-dev-mode-mcp-server": {
      "serverUrl": "https://mcp.figma.com/mcp"
    },
    "rea": {
      "command": "cmd.exe",
      "args": [
        "/c",
        "rea",
        "--mcp"
      ]
    }
  }
}
```

---

## 3. How to Install & Use REA in Other IDEs

You can use REA with any AI coding assistant or IDE that supports the **Model Context Protocol (MCP)** via standard I/O (stdio).

### Prerequisites
1. Ensure Node.js (v18+) is installed.
2. Install REA globally:
   ```bash
   npm install -g rea-agents
   ```

---

### A. Cursor
1. Open **Cursor Settings** (`Ctrl + ,` or click gear icon).
2. Go to **Features** → **MCP Servers**.
3. Click **Add New MCP Server**.
4. Set the fields:
   - **Name**: `rea`
   - **Type**: `stdio`
   - **Command**:
     - *On Windows*: `cmd.exe /c rea --mcp`
     - *On macOS / Linux*: `rea --mcp` (or `npx -y rea-agents@latest --mcp`)
5. Save. Cursor will connect to the server and surface 250+ reverse engineering tools to the agent.

Or edit `~/.cursor/mcp.json` (or `.cursor/mcp.json` inside your project):

```json
{
  "mcpServers": {
    "rea": {
      "command": "cmd.exe",
      "args": ["/c", "rea", "--mcp"]
    }
  }
}
```

---

### B. Claude Desktop
1. Open the Claude Desktop configuration file:
   - *Windows*: `%APPDATA%\Claude\claude_desktop_config.json`
   - *macOS*: `~/Library/Application Support/Claude/claude_desktop_config.json`
2. Add the `rea` server entry:

```json
{
  "mcpServers": {
    "rea": {
      "command": "cmd.exe",
      "args": ["/c", "rea", "--mcp"]
    }
  }
}
```
*(On macOS/Linux, replace `"command": "cmd.exe"` and `"args": ["/c", ...]` with `"command": "rea"`, `"args": ["--mcp"]`)*.
3. Completely quit and restart Claude Desktop. The hammer icon will show all REA tools.

---

### C. VS Code (Cline / Roo Code)
If using the **Cline** or **Roo Code** extension in VS Code:
1. Click the **MCP** icon in the Cline panel.
2. Click **Configure MCP Servers** (opens `cline_mcp_settings.json`).
3. Add the following entry under `"mcpServers"`:

```json
{
  "mcpServers": {
    "rea": {
      "command": "cmd.exe",
      "args": ["/c", "rea", "--mcp"],
      "disabled": false,
      "autoApprove": []
    }
  }
}
```
4. Save the file. The server will start automatically.

---

### D. Windsurf
1. Open Windsurf MCP configuration (`~/.codeium/windsurf/mcp_config.json`).
2. Add:

```json
{
  "mcpServers": {
    "rea": {
      "command": "cmd.exe",
      "args": ["/c", "rea", "--mcp"]
    }
  }
}
```
3. Restart Windsurf.

---

### E. Direct CLI Usage (Outside MCP)
You can also run REA directly in your terminal for standalone analysis:

```bash
# Analyze an application folder or binary
rea analyze "C:\Path\To\Application"

# Search for procedures or strings
rea search "C:\Path\To\Binary" "pattern"

# Statically reconstruct a JavaScript/Electron bundle
rea analyze-javascript-application "C:\Path\To\App" --json

# Verify MCP adapter status
rea mcp doctor
```

---

## 4. Reverse Engineering the Ostad Desktop App

### Locating the Target
Through Windows shortcut resolution (`Ostad.lnk` in Start Menu Programs), the desktop application was located at:
```
C:\Users\GoutamSharma\AppData\Local\Ostad\ostad.exe
```

### Architecture Discovery
Inspecting the application directory revealed:
- Built with **Flutter Desktop (Windows)**.
- Core native runtime libraries: `flutter_windows.dll`, `media_kit_video_plugin.dll`, `syncfusion_pdfviewer_windows_plugin.dll`, `sqlite3.dll`.
- Compiled Dart AOT binary: `data/app.so` (25.2 MB).
- Complete bundled UI assets: `data/flutter_assets/`.

### Analysis with REA
We ran `rea analyze` against the Ostad app folder, which mapped:
1. Complete asset inventory via `AssetManifest.json`.
2. Extracted class, repository, and controller symbols from `data/app.so`.
3. Discovered API routes under `https://api.ostad.app/api/v2`.

### Key Components & Workflows Extracted
From the reverse-engineered controllers and dialogs:

| Ostad Component / Symbol | Workflow / Feature |
| :--- | :--- |
| `AllTestsAssignmentCard`, `LearnerBatchAssignmentsController` | Module-wise student assignments with rubric points & due dates |
| `TeacherAssignmentLearnerListCard`, `TeacherSubmissionDetailView` | Instructor assignment grading, marks entry & personalized critique |
| `ResubmitLiveTestDialog`, `retest-submision-popup-icon.png` | Student request flow for assignment/test resubmission & score improvement |
| `LeaderboardScreenView`, `ProgressAndLeaderboardRepository` | Cohort-wide live leaderboard ranking students by earned points |
| `StudyPlanLiveMainCard`, `StudyPlanLiveConceptCard`, `StudyPlanScrumMeetingCard` | Three-tier live class taxonomy: Main Lectures, Doubt Clinics, Scrum Reviews |
| `JoinLiveController`, `TeacherLandingClassJoiningTabController` | Attendance logging with timestamps when students join live sessions |

---

## 5. What Work Was Implemented in Mono LMS

Based on the reverse engineering findings, we implemented the missing cohort-based learning engine natively in the Mono LMS Laravel web application:

### A. Assignments & Hands-on Projects Engine
1. **Migrations & Tables**:
   - `assignments`: `course_id`, `title`, `description`, `total_points`, `pass_points`, `due_date`, `allow_resubmission`, `status`, `sort_order`.
   - `assignment_submissions`: `assignment_id`, `student_id`, `submission_content`, `external_link`, `file_path`, `score`, `status`, `instructor_feedback`, `resubmission_note`, `graded_at`.
2. **Models**:
   - [`app/Models/Assignment.php`](file:///c:/laragon/www/mono-lms/app/Models/Assignment.php)
   - [`app/Models/AssignmentSubmission.php`](file:///c:/laragon/www/mono-lms/app/Models/AssignmentSubmission.php)
3. **Student Submission Portal**:
   - Students submit external links (GitHub repo, Figma, Google Drive, live URL), attach deliverables (ZIP, PDF, DOCX), or submit written notes.
4. **Grading & Resubmission System**:
   - Instructors and Admins evaluate submissions, assign scores, and provide feedback.
   - If `allow_resubmission` is enabled, students can submit a retest / resubmission request with an explanation note (replicating Ostad's workflow).

### B. Cohort Leaderboard & Gamification
1. **Leaderboard Engine**:
   - Added `cohortLeaderboard()` in [`app/Models/Course.php`](file:///c:/laragon/www/mono-lms/app/Models/Course.php).
   - Aggregates score dynamically from:
     - Graded assignment scores
     - Passed quiz scores
     - Live class attendance points (10 points per attended session)
2. **Dedicated Leaderboard View**:
   - Created [`resources/views/pages/student/leaderboard.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/student/leaderboard.blade.php).
   - Features a **Top 3 Podium** (🥇 1st Place Gold, 🥈 2nd Place Silver, 🥉 3rd Place Bronze).
   - Displays student's personal rank badge and full peer table with points breakdown.
3. **Learn Player Integration**:
   - Added a compact cohort leaderboard widget directly in the lesson player sidebar [`resources/views/pages/student/learn.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/student/learn.blade.php).

### C. Live Class Session Types & Attendance Tracking
1. **Class Taxonomy**:
   - Added `class_type` to [`app/Models/LiveClass.php`](file:///c:/laragon/www/mono-lms/app/Models/LiveClass.php) with three categories:
     - `Live Main Class` (Core syllabus lecture)
     - `Conceptual / Doubt Clearing` (Hands-on problem solving clinic)
     - `Scrum & Progress Review` (Milestone progress check-in)
2. **Automated Attendance Logging**:
   - Created [`app/Models/LiveClassAttendance.php`](file:///c:/laragon/www/mono-lms/app/Models/LiveClassAttendance.php).
   - In [`app/Http/Controllers/Student/LearnController.php`](file:///c:/laragon/www/mono-lms/app/Http/Controllers/Student/LearnController.php), clicking "Join Class" automatically creates an attendance record with a timestamp before redirecting to Zoom/Meet.
   - Students see an "Attended ✓" badge on the session card.
3. **Attendance Roster for Instructors & Admins**:
   - Course settings view now includes an attendance tracking drawer listing every student who joined each live class.

### D. UI Views & Integration
- **Student Assignment View**: [`resources/views/pages/student/assignments/show.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/student/assignments/show.blade.php)
- **Course Assignment Manager**: [`resources/views/pages/admin/partials/course-assignments-manager.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/admin/partials/course-assignments-manager.blade.php)
- **Submissions & Grading View**: [`resources/views/pages/admin/assignments/submissions.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/admin/assignments/submissions.blade.php)
- **Course Learn Page**: [`resources/views/pages/student/learn.blade.php`](file:///c:/laragon/www/mono-lms/resources/views/pages/student/learn.blade.php) (enhanced with Live Class badges, Assignments list, and Cohort Leaderboard card)

---

## 6. Testing, Verification & Git Sync

### Automated Testing
- Created a comprehensive test suite in [`tests/Feature/CohortAssignmentSystemTest.php`](file:///c:/laragon/www/mono-lms/tests/Feature/CohortAssignmentSystemTest.php) covering:
  - Instructor assignment creation
  - Admin assignment updates and deletions
  - Student assignment submissions with project links & text
  - Instructor grading and critique feedback
  - Student resubmission request flow
  - Live class join attendance tracking
  - Cohort leaderboard ranking and point calculations
- **Test Result**: **248/248 tests passed** (1,446 assertions).

### Code Formatting
- Code formatted with **Laravel Pint**:
  ```bash
  vendor/bin/pint --dirty --format agent
  ```

### Version Control
- All changes committed and pushed to GitHub repository:
  - Commit: `3a3dcf5`
  - Branch: `main`
